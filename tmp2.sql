select *
from `users`
where (
        exists (
            select *
            from
                `applicants`
                inner join `user_applicant_xrefs` on `applicants`.`id` = `user_applicant_xrefs`.`applicant_id`
            where
                `users`.`id` = `user_applicant_xrefs`.`user_id`
                and exists (
                    select *
                    from
                        `users`
                        inner join `user_applicant_xrefs` on `user_applicant_xrefs`.`user_id` = `users`.`id`
                    where
                        `applicants`.`id` = `user_applicant_xrefs`.`applicant_id`
                        and exists (
                            select *
                            from `camp_org`
                                inner join `org_user` on `camp_org`.`id` = `org_user`.`org_id`
                            where
                                `users`.`id` = `org_user`.`user_id`
                                and `org_user`.`user_type` = 'App\Models\User'
                                and `camp_id` = 77
                        )
                )
                and `applicants`.`deleted_at` is null
        )
        or not exists (
            select *
            from
                `applicants`
                inner join `user_applicant_xrefs` on `applicants`.`id` = `user_applicant_xrefs`.`applicant_id`
            where
                `users`.`id` = `user_applicant_xrefs`.`user_id`
                and exists (
                    select *
                    from
                        `users`
                        inner join `user_applicant_xrefs` on `user_applicant_xrefs`.`user_id` = `users`.`id`
                    where
                        `applicants`.`id` = `user_applicant_xrefs`.`applicant_id`
                        and exists (
                            select *
                            from `camp_org`
                                inner join `org_user` on `camp_org`.`id` = `org_user`.`org_id`
                            where
                                `users`.`id` = `org_user`.`user_id`
                                and `org_user`.`user_type` = 'App\Models\User'
                                and `camp_id` = 77
                        )
                )
                and `applicants`.`deleted_at` is null
        )
    )
    and exists (
        select *
        from
            `applicants`
            inner join `user_applicant_xrefs` on `applicants`.`id` = `user_applicant_xrefs`.`applicant_id`
            inner join `evcamp` on `applicants`.`id` = `evcamp`.`applicant_id`
        where
            `users`.`id` = `user_applicant_xrefs`.`user_id`
            and `batch_id` in (167, 168)
            and `applicants`.`deleted_at` is null
    )
