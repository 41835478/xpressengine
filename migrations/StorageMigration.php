<?php
/**
 * @author    XE Developers <developers@xpressengine.com>
 * @copyright 2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license   http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html LGPL-2.1
 * @link      https://xpressengine.io
 */

namespace Xpressengine\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use Xpressengine\Support\Migration;

class StorageMigration extends Migration {

    public function install()
    {

        Schema::create('files', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->string('id', 36)->comment('file ID');
            $table->string('originId', 36)->nullable()->comment('original file ID');
            $table->string('userId', 36)->nullable()->comment('own user ID');
            $table->string('disk', 20)->charset('latin1')->comment('storage locale.');
            $table->string('path')->charset('latin1')->comment('registered file path. without name');
            $table->string('filename', 100)->charset('latin1')->comment('registered file name. without extension');
            $table->string('clientname', 100)->comment('original file name');
            $table->string('mime', 50)->comment('mime type');
            $table->integer('size')->comment('file size');
            $table->integer('useCount')->default(0)->comment('use count. how much used in the system.');
            $table->integer('downloadCount')->default(0)->comment('download count');
            $table->timestamp('createdAt')->comment('created date');
            $table->timestamp('updatedAt')->comment('updated date');

            $table->primary('id');
            $table->unique(['disk', 'path', 'filename'], 'findKey');
            $table->index('originId');
        });

        Schema::create('fileables', function (Blueprint $table) {
            // mapping a file to target. If Document uploaded a file, [fileableId] is document ID.
            $table->increments('id')->comment('ID');
            $table->string('fileId', 36)->comment('file ID');
            $table->string('fileableId', 36)->comment('target ID. If Document uploaded a file, [fileableId] is document ID.');
            $table->timestamp('createdAt')->comment('created date');

            $table->unique(['fileId', 'fileableId']);
        });
    }
}
