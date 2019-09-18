<?php
/**
 * Created by PhpStorm.
 * User: Iulian
 * Date: 5/12/2019
 * Time: 8:58 PM
 */

namespace App\Http\Controllers;


class MasterController extends Controller{
    /**
     * Clear laravel.log
     */
    public function clearLog() {
        $log = storage_path( 'logs/laravel.log' );
        if ( file_exists( $log ) ) {
            unlink( $log );
        }
        echo "Log deleted successfully";
    }

    /**
     * read laravel.log
     * @param string $index
     */
    public function readLog( $index = '') {
        $index = empty($index) && $index !== '0' ? '' : '_' . $index;
        if (file_exists(storage_path('logs/laravel.log' . $index))) {
            echo nl2br(file_get_contents(storage_path('logs/laravel.log' . $index)));
        } else {
            echo 'the file is empty or does not exist';
        }
    }
}