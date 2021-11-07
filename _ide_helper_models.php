<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Acamp
 *
 * @property int $id
 * @property int $applicant_id
 * @property string|null $unit
 * @property string|null $unit_county
 * @property string|null $unit_subarea
 * @property string|null $industry
 * @property string|null $title
 * @property string|null $education
 * @property string|null $job_property
 * @property int|null $is_manager
 * @property int|null $is_cadre
 * @property int|null $is_technical_staff
 * @property string|null $class_location
 * @property string|null $way
 * @property string|null $belief
 * @property string|null $motivation
 * @property string|null $motivation_other
 * @property string|null $blisswisdom_type
 * @property string|null $blisswisdom_type_other
 * @property string|null $transportation
 * @property int $is_inperson
 * @property string|null $agent_name
 * @property string|null $agent_phone
 * @property string|null $agent_relationship
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp query()
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereAgentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereAgentPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereAgentRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereApplicantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereBelief($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereBlisswisdomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereBlisswisdomTypeOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereClassLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereEducation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereIndustry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereIsCadre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereIsInperson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereIsManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereIsTechnicalStaff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereJobProperty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereMotivation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereMotivationOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereTransportation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereUnitCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereUnitSubarea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Acamp whereWay($value)
 */
	class Acamp extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Applicant
 *
 * @property int $id
 * @property int $batch_id
 * @property string $name
 * @property int $is_admitted
 * @property string|null $region
 * @property string|null $group
 * @property string|null $number
 * @property int|null $is_attend
 * @property string $gender
 * @property int|null $birthyear
 * @property int|null $birthmonth
 * @property int|null $birthday
 * @property string|null $age_range
 * @property string|null $nationality
 * @property string|null $idno
 * @property int|null $is_foreigner
 * @property int $is_allow_notified
 * @property string|null $mobile
 * @property string|null $phone_home
 * @property string|null $phone_work
 * @property string|null $fax
 * @property string|null $line
 * @property string|null $wechat
 * @property string|null $email
 * @property string|null $zipcode
 * @property string|null $address
 * @property string|null $emergency_name
 * @property string|null $emergency_relationship
 * @property string|null $emergency_mobile
 * @property string|null $emergency_phone_home
 * @property string|null $emergency_phone_work
 * @property string|null $emergency_fax
 * @property string|null $introducer_name
 * @property string|null $introducer_relationship
 * @property string|null $introducer_phone
 * @property string|null $introducer_participated
 * @property int $portrait_agree
 * @property int $profile_agree
 * @property string|null $expectation
 * @property string|null $store_first_barcode
 * @property string|null $store_second_barcode
 * @property string|null $store_third_barcode
 * @property string|null $bank_second_barcode
 * @property string|null $bank_third_barcode
 * @property int $fee
 * @property int $deposit
 * @property string|null $tax_id_no
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Acamp|null $acamp
 * @property-read \App\Models\Batch $batch
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CheckIn[] $checkInData
 * @property-read int|null $check_in_data_count
 * @property-read \App\Models\Ecamp|null $ecamp
 * @property-read \App\Models\Hcamp|null $hcamp
 * @property-read \App\Models\Tcamp|null $tcamp
 * @property-read \App\Models\Ycamp|null $ycamp
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant newQuery()
 * @method static \Illuminate\Database\Query\Builder|Applicant onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereAgeRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereBankSecondBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereBankThirdBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereBirthmonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereBirthyear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereEmergencyFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereEmergencyMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereEmergencyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereEmergencyPhoneHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereEmergencyPhoneWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereEmergencyRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereExpectation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereIdno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereIntroducerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereIntroducerParticipated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereIntroducerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereIntroducerRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereIsAdmitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereIsAllowNotified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereIsAttend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereIsForeigner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant wherePhoneHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant wherePhoneWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant wherePortraitAgree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereProfileAgree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereStoreFirstBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereStoreSecondBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereStoreThirdBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereTaxIdNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereWechat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Applicant whereZipcode($value)
 * @method static \Illuminate\Database\Query\Builder|Applicant withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Applicant withoutTrashed()
 */
	class Applicant extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Batch
 *
 * @property int $id
 * @property int $camp_id
 * @property string $name
 * @property string|null $admission_suffix
 * @property string|null $batch_start
 * @property string|null $batch_end
 * @property int $is_appliable
 * @property int $is_late_registration_end
 * @property string|null $late_registration_end
 * @property string|null $locationName
 * @property string|null $location
 * @property string|null $check_in_day
 * @property string|null $tel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Camp $camp
 * @method static \Illuminate\Database\Eloquent\Builder|Batch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Batch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Batch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereAdmissionSuffix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereBatchEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereBatchStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereCheckInDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereIsAppliable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereIsLateRegistrationEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereLateRegistrationEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereLocationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Batch whereUpdatedAt($value)
 */
	class Batch extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Camp
 *
 * @property int $id
 * @property string $fullName
 * @property int $test
 * @property string|null $site_url
 * @property string $abbreviation
 * @property string|null $icon
 * @property string $table
 * @property string|null $variant
 * @property string $registration_start
 * @property string $registration_end
 * @property int $has_early_bird
 * @property int $early_bird_fee
 * @property string|null $early_bird_last_day
 * @property string $admission_announcing_date
 * @property string|null $admission_confirming_end
 * @property string|null $final_registration_end
 * @property string|null $payment_startdate
 * @property string|null $payment_deadline
 * @property int|null $fee
 * @property string|null $modifying_deadline
 * @property \Illuminate\Support\Carbon|null $cancellation_deadline
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Batch[] $batchs
 * @property-read int|null $batchs_count
 * @property-read mixed $set_fee
 * @property-read mixed $set_payment_deadline
 * @method static \Illuminate\Database\Eloquent\Builder|Camp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Camp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Camp query()
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereAdmissionAnnouncingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereAdmissionConfirmingEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereCancellationDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereEarlyBirdFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereEarlyBirdLastDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereFinalRegistrationEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereHasEarlyBird($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereModifyingDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp wherePaymentDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp wherePaymentStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereRegistrationEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereRegistrationStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereSiteUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Camp whereVariant($value)
 */
	class Camp extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CheckIn
 *
 * @property int $id
 * @property int $applicant_id
 * @property int|null $checker_id
 * @property string|null $check_in_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Applicant $applicant
 * @method static \Illuminate\Database\Eloquent\Builder|CheckIn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckIn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckIn query()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckIn whereApplicantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckIn whereCheckInDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckIn whereCheckerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckIn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckIn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckIn whereUpdatedAt($value)
 */
	class CheckIn extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Ecamp
 *
 * @property int $id
 * @property int $applicant_id
 * @property string|null $belief
 * @property string|null $education
 * @property string|null $unit
 * @property string|null $unit_location
 * @property string|null $title
 * @property string|null $level
 * @property string|null $job_property
 * @property string|null $experience
 * @property string|null $employees
 * @property string|null $direct_managed_employees
 * @property string|null $industry
 * @property string|null $after_camp_available_day
 * @property string|null $favored_event
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereAfterCampAvailableDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereApplicantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereBelief($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereDirectManagedEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereEducation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereFavoredEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereIndustry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereJobProperty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereUnitLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ecamp whereUpdatedAt($value)
 */
	class Ecamp extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Hcamp
 *
 * @property int $id
 * @property int $applicant_id
 * @property string|null $education
 * @property string|null $special_condition
 * @property string|null $traffic_depart
 * @property string|null $traffic_return
 * @property string|null $branch_or_classroom_belongs_to
 * @property string|null $class_type
 * @property string|null $parent_lamrim_class
 * @property int $is_recommended_by_reading_class
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_lamrim
 * @property string|null $is_child_blisswisdommed
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp query()
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereApplicantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereBranchOrClassroomBelongsTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereClassType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereEducation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereIsChildBlisswisdommed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereIsLamrim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereIsRecommendedByReadingClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereParentLamrimClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereSpecialCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereTrafficDepart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereTrafficReturn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hcamp whereUpdatedAt($value)
 */
	class Hcamp extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property int $level
 * @property string $name
 * @property int|null $camp_id
 * @property string|null $region
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Camp|null $camp
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RoleUser[] $role_users
 * @property-read int|null $role_users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RoleUser
 *
 * @property int $id
 * @property int $role_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Role $role
 * @property-read \App\Models\Role $role_data
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereUserId($value)
 */
	class RoleUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tcamp
 *
 * @property int $id
 * @property int $applicant_id
 * @property int|null $is_educating
 * @property int|null $has_license
 * @property string|null $workshop_credit_type
 * @property string|null $never_attend_any_stay_over_tcamps
 * @property string|null $info_source
 * @property string|null $interesting
 * @property string|null $interesting_complement
 * @property int|null $years_teached
 * @property string|null $education
 * @property string|null $school_or_course
 * @property string|null $subject_teaches
 * @property string|null $position
 * @property string|null $title
 * @property string|null $unit
 * @property string|null $unit_county
 * @property string|null $unit_district
 * @property int $is_blisswisdom
 * @property string|null $blisswisdom_type
 * @property string|null $blisswisdom_type_complement
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereApplicantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereBlisswisdomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereBlisswisdomTypeComplement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereEducation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereHasLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereInfoSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereInteresting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereInterestingComplement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereIsBlisswisdom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereIsEducating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereNeverAttendAnyStayOverTcamps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereSchoolOrCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereSubjectTeaches($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereUnitCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereUnitDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereWorkshopCreditType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tcamp whereYearsTeached($value)
 */
	class Tcamp extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Traffic
 *
 * @property int $id
 * @property int $applicant_id
 * @property string|null $depart_from
 * @property string|null $back_to
 * @property int $fare
 * @property int $deposit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Applicant $applicant
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereApplicantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereBackTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereDepartFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereFare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traffic whereUpdatedAt($value)
 */
	class Traffic extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Ycamp
 *
 * @property int $id
 * @property int $applicant_id
 * @property string|null $school
 * @property string|null $school_location
 * @property string|null $day_night
 * @property string|null $system
 * @property string|null $department
 * @property string|null $grade
 * @property string|null $way
 * @property int $is_blisswisdom
 * @property string|null $blisswisdom_type
 * @property string|null $blisswisdom_type_other
 * @property string|null $father_name
 * @property string|null $father_lamrim
 * @property string|null $father_phone
 * @property string|null $mother_name
 * @property string|null $mother_lamrim
 * @property string|null $mother_phone
 * @property int $is_inperson
 * @property string|null $agent_name
 * @property string|null $agent_phone
 * @property string|null $habbit
 * @property string|null $club
 * @property string|null $goal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereAgentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereAgentPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereApplicantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereBlisswisdomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereBlisswisdomTypeOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereClub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereDayNight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereFatherLamrim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereFatherPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereGoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereHabbit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereIsBlisswisdom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereIsInperson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereMotherLamrim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereMotherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereMotherPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereSchoolLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ycamp whereWay($value)
 */
	class Ycamp extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $permission
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RoleUser[] $role_relations
 * @property-read int|null $role_relations_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

