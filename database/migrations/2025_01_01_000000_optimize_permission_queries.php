<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OptimizePermissionQueries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 最佳化 role_user 表的索引
        Schema::table('role_user', function (Blueprint $table) {
            // 複合索引用於快速查詢使用者在特定營隊的角色
            if (!Schema::hasIndex('role_user', 'idx_user_camp_role')) {
                $table->index(['user_id', 'camp_id'], 'idx_user_camp_role');
            }
        });

        // 最佳化 permissions 表的索引
        Schema::table('permissions', function (Blueprint $table) {
            // 複合索引用於快速查詢特定資源和動作的權限
            if (!Schema::hasIndex('permissions', 'idx_resource_action')) {
                $table->index(['resource', 'action'], 'idx_resource_action');
            }

            // 索引用於批次和區域的權限查詢
            if (!Schema::hasIndex('permissions', 'idx_batch_region')) {
                $table->index(['batch_id', 'region_id'], 'idx_batch_region');
            }

            // 索引用於營隊權限查詢
            if (!Schema::hasIndex('permissions', 'idx_camp_permissions')) {
                $table->index('camp_id', 'idx_camp_permissions');
            }
        });

        // 最佳化 permission_role 表的索引
        Schema::table('permission_role', function (Blueprint $table) {
            // 複合索引用於快速查詢角色的權限
            if (!Schema::hasIndex('permission_role', 'idx_role_permission')) {
                $table->index(['role_id', 'permission_id'], 'idx_role_permission');
            }
        });

                // 最佳化 roles 表的索引
        Schema::table('roles', function (Blueprint $table) {
            // 索引用於查詢營隊角色
            if (!Schema::hasIndex('roles', 'idx_camp_roles')) {
                $table->index('camp_id', 'idx_camp_roles');
            }

            // 複合索引用於查詢特定小組或區域的角色
            if (!Schema::hasIndex('roles', 'idx_group_region')) {
                $table->index(['group_id', 'region_id'], 'idx_group_region');
            }

            // 索引用於查詢部門
            if (!Schema::hasIndex('roles', 'idx_section')) {
                $table->index('section', 'idx_section');
            }
        });

        // 最佳化 carer_applicant_xrefs 表的索引
        Schema::table('carer_applicant_xrefs', function (Blueprint $table) {
            // 複合索引用於查詢關懷員和學員的關係
            if (!Schema::hasIndex('carer_applicant_xrefs', 'idx_user_applicant')) {
                $table->index(['user_id', 'applicant_id'], 'idx_user_applicant');
            }

            // 索引用於查詢特定梯次的關懷關係
            if (!Schema::hasIndex('carer_applicant_xrefs', 'idx_batch_carer')) {
                $table->index(['batch_id', 'user_id'], 'idx_batch_carer');
            }
        });

        // 最佳化 applicants 表的索引（如果尚未最佳化）
        Schema::table('applicants', function (Blueprint $table) {
            // 複合索引用於權限檢查時的批次和區域查詢
            if (!Schema::hasIndex('applicants', 'idx_batch_region_group')) {
                $table->index(['batch_id', 'region_id', 'group_id'], 'idx_batch_region_group');
            }
        });

        // 最佳化 volunteers 表的索引
        Schema::table('volunteers', function (Blueprint $table) {
            // 複合索引用於權限檢查時的批次和區域查詢
            if (!Schema::hasIndex('volunteers', 'idx_batch_region_user')) {
                $table->index(['batch_id', 'region_id', 'user_id'], 'idx_batch_region_user');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropIndex('idx_user_camp_role');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropIndex('idx_resource_action');
            $table->dropIndex('idx_batch_region');
            $table->dropIndex('idx_camp_permissions');
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->dropIndex('idx_role_permission');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropIndex('idx_camp_roles');
            $table->dropIndex('idx_group_region');
            $table->dropIndex('idx_section');
        });

        Schema::table('carer_applicant_xrefs', function (Blueprint $table) {
            $table->dropIndex('idx_user_applicant');
            $table->dropIndex('idx_batch_carer');
        });

        Schema::table('applicants', function (Blueprint $table) {
            $table->dropIndex('idx_batch_region_group');
        });

        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropIndex('idx_batch_region_user');
        });
    }
}
