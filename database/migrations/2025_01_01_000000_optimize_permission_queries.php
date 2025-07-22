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
        try {
            Schema::table('role_user', function (Blueprint $table) {
                // 複合索引用於快速查詢使用者在特定營隊的角色
                $table->index(['user_id', 'camp_id'], 'idx_user_camp_role');
            });
        } catch (\Exception $e) {
            // 索引可能已存在，忽略錯誤
        }

        // 最佳化 permissions 表的索引
        try {
            Schema::table('permissions', function (Blueprint $table) {
                // 複合索引用於快速查詢特定資源和動作的權限
                $table->index(['resource', 'action'], 'idx_resource_action');
            });
        } catch (\Exception $e) {
            // 索引可能已存在，忽略錯誤
        }

        try {
            Schema::table('permissions', function (Blueprint $table) {
                // 索引用於批次和區域的權限查詢
                $table->index(['batch_id', 'region_id'], 'idx_batch_region');
            });
        } catch (\Exception $e) {
            // 索引可能已存在，忽略錯誤
        }

        try {
            Schema::table('permissions', function (Blueprint $table) {
                // 索引用於營隊權限查詢
                $table->index('camp_id', 'idx_camp_permissions');
            });
        } catch (\Exception $e) {
            // 索引可能已存在，忽略錯誤
        }

        // 最佳化 permission_role 表的索引
        try {
            Schema::table('permission_role', function (Blueprint $table) {
                // 複合索引用於快速查詢角色的權限
                $table->index(['role_id', 'permission_id'], 'idx_role_permission');
            });
        } catch (\Exception $e) {
            // 索引可能已存在，忽略錯誤
        }

        // 最佳化 roles 表的索引
        try {
            Schema::table('roles', function (Blueprint $table) {
                // 索引用於查詢營隊角色
                $table->index('camp_id', 'idx_camp_roles');
            });
        } catch (\Exception $e) {
            // 索引可能已存在，忽略錯誤
        }

        try {
            Schema::table('roles', function (Blueprint $table) {
                // 複合索引用於查詢特定小組或區域的角色
                $table->index(['group_id', 'region_id'], 'idx_group_region');
            });
        } catch (\Exception $e) {
            // 索引可能已存在，忽略錯誤
        }

        try {
            Schema::table('roles', function (Blueprint $table) {
                // 索引用於查詢部門
                $table->index('section', 'idx_section');
            });
        } catch (\Exception $e) {
            // 索引可能已存在，忽略錯誤
        }

        // 最佳化 carer_applicant_xrefs 表的索引
        try {
            Schema::table('carer_applicant_xrefs', function (Blueprint $table) {
                // 複合索引用於查詢關懷員和學員的關係
                $table->index(['user_id', 'applicant_id'], 'idx_user_applicant');
            });
        } catch (\Exception $e) {
            // 索引可能已存在，忽略錯誤
        }

        try {
            Schema::table('carer_applicant_xrefs', function (Blueprint $table) {
                // 索引用於查詢特定梯次的關懷關係
                $table->index(['batch_id', 'user_id'], 'idx_batch_carer');
            });
        } catch (\Exception $e) {
            // 索引可能已存在，忽略錯誤
        }

        // 最佳化 applicants 表的索引（如果尚未最佳化）
        try {
            Schema::table('applicants', function (Blueprint $table) {
                // 複合索引用於權限檢查時的批次和區域查詢
                $table->index(['batch_id', 'region_id', 'group_id'], 'idx_batch_region_group');
            });
        } catch (\Exception $e) {
            // 索引可能已存在，忽略錯誤
        }

        // 最佳化 volunteers 表的索引
        try {
            Schema::table('volunteers', function (Blueprint $table) {
                // 複合索引用於權限檢查時的批次和區域查詢
                $table->index(['batch_id', 'region_id', 'user_id'], 'idx_batch_region_user');
            });
        } catch (\Exception $e) {
            // 索引可能已存在，忽略錯誤
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            Schema::table('role_user', function (Blueprint $table) {
                $table->dropIndex('idx_user_camp_role');
            });
        } catch (\Exception $e) {
            // 索引可能不存在，忽略錯誤
        }

        try {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropIndex('idx_resource_action');
            });
        } catch (\Exception $e) {
            // 索引可能不存在，忽略錯誤
        }

        try {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropIndex('idx_batch_region');
            });
        } catch (\Exception $e) {
            // 索引可能不存在，忽略錯誤
        }

        try {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropIndex('idx_camp_permissions');
            });
        } catch (\Exception $e) {
            // 索引可能不存在，忽略錯誤
        }

        try {
            Schema::table('permission_role', function (Blueprint $table) {
                $table->dropIndex('idx_role_permission');
            });
        } catch (\Exception $e) {
            // 索引可能不存在，忽略錯誤
        }

        try {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropIndex('idx_camp_roles');
            });
        } catch (\Exception $e) {
            // 索引可能不存在，忽略錯誤
        }

        try {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropIndex('idx_group_region');
            });
        } catch (\Exception $e) {
            // 索引可能不存在，忽略錯誤
        }

        try {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropIndex('idx_section');
            });
        } catch (\Exception $e) {
            // 索引可能不存在，忽略錯誤
        }

        try {
            Schema::table('carer_applicant_xrefs', function (Blueprint $table) {
                $table->dropIndex('idx_user_applicant');
            });
        } catch (\Exception $e) {
            // 索引可能不存在，忽略錯誤
        }

        try {
            Schema::table('carer_applicant_xrefs', function (Blueprint $table) {
                $table->dropIndex('idx_batch_carer');
            });
        } catch (\Exception $e) {
            // 索引可能不存在，忽略錯誤
        }

        try {
            Schema::table('applicants', function (Blueprint $table) {
                $table->dropIndex('idx_batch_region_group');
            });
        } catch (\Exception $e) {
            // 索引可能不存在，忽略錯誤
        }

        try {
            Schema::table('volunteers', function (Blueprint $table) {
                $table->dropIndex('idx_batch_region_user');
            });
        } catch (\Exception $e) {
            // 索引可能不存在，忽略錯誤
        }
    }
}
