<?php if (!class_exists('UncodeHotfix')): ?>
<?php
class UncodeHotfix extends UncodeAPI {
    function __construct($baseUrl='http://static.undsgn.com/uncode/endpoint') {
        parent::__construct($baseUrl);
    }

    /**
     * Get all available patches
     *
     * @return Array<Object>
     */
    function getAllPatches() {
        $response = $this->request('patches.json');

        if (!empty($response)) { $response = json_decode($response); }

        return $response;
    }

    /**
     * Get patches that is relevant for current installation
     *
     * @return Array<Object>
     */
    function getRelevantPatches() {
        $_patches = array();
        $wp_version = get_bloginfo('version');
        $theme = wp_get_theme();
        $theme_version =  $theme->get('Version');
        $patches = $this->getAllPatches();
        $c_patches = $this->getCommittedPatches();

        $dates = array_keys(get_object_vars($patches));

        foreach ($dates as $date) {
            $date_patches = $patches->$date;

            foreach ($date_patches as $patch) {
                $patch = (Array) $patch;
                $keep = true;

                $file = get_template_directory() . '/' . $patch['path'];
                $local_md5 = md5_file($file);
                
                if (isset($patch['theme-version']) && isset($patch['wp-version'])) {
                    if (
                        !empty($patch['theme-version']) ||
                        !empty($patch['wp-version'])
                    ) {
                        if (
                            $patch['theme-version'] != $theme_version ||
                            $patch['wp-version'] != $wp_version
                        ) {
                            continue;
                        }
                    }
                }

                if ($local_md5 == $patch['md5']) {
                    continue;
                }

                $patch['date'] = $date;
                $patch['merged'] = false;
                $patch['id'] = md5($patch['md5'] . $patch['path'] . $date);

                foreach ($c_patches as $c_patch) {
                    if ($patch['id'] == $c_patch->id) {
                        $keep = false;
                    }
                }
                
                if ($keep) {
                    $_patches[] = $patch;
                }
            }
        }

        return $_patches;
    }
    
    /**
     * Get single committed patch from database by id
     *
     * @param Integer $patch_id
     *
     * @return Object
     */ 
    function getCommittedPatch($patch_id) {
        foreach ($this->getCommittedPatches() as $e_patch) {
            if ($e_patch->id == $patch_id) {
                return $e_patch;
            }
        }

        return null;
    }

    /**
     * Count available patches
     *
     * @return Integer
     */
    function countRelevantPatches() {
        return sizeof($this->getRelevantPatches());
    }

    /**
     * Get committed patches from database
     *
     * @return Array<Object>
     */
    function getCommittedPatches($filter=null, $sortby=null) {
        $existing_patches = get_option('uncode_patches');
        $_patches = array();

        if (empty($existing_patches)) { return $_patches; }

        if (empty($filter)) {
            $_patches = !empty($existing_patches) ?
                json_decode($existing_patches) : array();
        }

        if (!empty($existing_patches)) {
            $existing_patches = json_decode($existing_patches);
        }

        foreach ($existing_patches as $e_patch) {
            $e_patch = (Array) $e_patch;

            if (isset($e_patch[$filter['key']])) {
                if ($e_patch[$filter['key']] == $filter['value']) {
                    $_patches[] = (Object) $e_patch;
                }
            }
        }

        if (!empty($sortby)) {
            usort($_patches, $sortby);
        }

        return $_patches;
    }

    /**
     * Count committed patches from database
     *
     * @return Integer
     */
    function countCommittedPatches($filter=null) {
        return sizeof($this->getCommittedPatches($filter, null));
    }
    
    /**
     * Save/commit single patch to database
     *
     * @param Object $patch
     *
     * @return Boolean
     */
    function commitPatch($patch) {
        $_patches = array();
        $existing_patches = $this->getCommittedPatches();

        foreach ($existing_patches as $e_patch) {
            if ($e_patch->id == $patch->id) {
                $e_patch = $patch;
            }

            $_patches[] = $e_patch;
        }

        return update_option('uncode_patches', json_encode($_patches));
    }
    
    /**
     * Commit relevant patches to database
     *
     * @return Boolean
     */
    function commitPatches() {
        $existing_patches = $this->getCommittedPatches();
        $r_patches = $this->getRelevantPatches();

        foreach ($r_patches as $r_patch) {
            $exists = false;
            $r_patch_id = md5(
                $r_patch['md5'] . $r_patch['path'] . $r_patch['date']
            );

            foreach ($existing_patches as $e_patch) {
                $e_patch = (Array) $e_patch;

                if ($r_patch_id == $e_patch['id']) { $exists = true; }
            }

            if (!$exists) {
                $existing_patches[] = $r_patch;
            }
        }

        return update_option('uncode_patches', json_encode($existing_patches));
    }

    /**
     * Remove a patch from the database
     *
     * @param Object $patch
     *
     * @return Boolean
     */
    function uncommitPatch($patch) {
        $_patches = array();
        $existing_patches = $this->getCommittedPatches();

        foreach ($existing_patches as $e_patch) {
            if ($e_patch->id == $patch->id) {
                continue;
            }

            $_patches[] = $e_patch;
        }

        return update_option('uncode_patches', json_encode($_patches));
    }

    /**
     * Merge all patches from database.
     * Replacing all files with files from patches.
     *
     * @return Boolean
     */
    function mergePatches() {
        $patches = $this->getCommittedPatches();

        if (sizeof($patches) == 0) { return false; }

        foreach ($patches as $patch) {
            $this->mergePatch($patch);
        }

        return true;
    }

    /**
     * Merge patch, replace local file with file from patch etc...
     *
     * @param Object $patch
     *
     * @return Boolean
     */
    function mergePatch($patch) {
        $error = null;
        
        $patch = (Array)$patch;
        
        set_error_handler(function() {
            $error = '[ERROR]: Request failed';
        });

        $new_file_contents = file_get_contents($patch['get']);
        restore_error_handler();
        
        if (!empty($new_file_contents)) {
            $ok = file_put_contents(
                get_template_directory() . '/' . $patch['path'],
                $new_file_contents
            );
            
            if ($ok) {
                $patch['merged'] = true;
                $patch['merged_at'] = time();
            } else {
                $error = '[ERROR]: Could not merge';
            }
        } else {
            $error = '[ERROR]: Could not get new file';
        }

        $patch['error'] = $error;
        
        $patch_merge = $this->commitPatch((Object)$patch);

        if (!empty($error)) {
            return false;
        }

        return $patch_merge;
    }
}
?>
<?php endif; ?>
