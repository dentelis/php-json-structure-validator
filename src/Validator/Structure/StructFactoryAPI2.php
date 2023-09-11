<?php

namespace EntelisTeam\Validator\Structure;

use EntelisTeam\Validator\Enum\_simpleType;
use Tests\Support\Middleware\Enum\ActiveProtectionStatusEnum;
use Tests\Support\Middleware\Enum\ApiStatusEnum;
use Tests\Support\Middleware\Enum\CardGroupTypeEnum;
use Tests\Support\Middleware\Enum\CardTypeEnum;
use Tests\Support\Middleware\Enum\CitizenTypeEnum;
use Tests\Support\Middleware\Enum\DMSGaranteeLetterStatusEnum;
use Tests\Support\Middleware\Enum\EventReportNsStatusEnum;
use Tests\Support\Middleware\Enum\FileStatusEnum;
use Tests\Support\Middleware\Enum\InsuranceCategoryTypeEnum;
use Tests\Support\Middleware\Enum\InsuranceEventReportTypeEnum;
use Tests\Support\Middleware\Enum\InsuranceRenewTypeEnum;
use Tests\Support\Middleware\Enum\InsuranceTypeEnum;
use Tests\Support\Middleware\Enum\NotificationStatusEnum;
use Tests\Support\Middleware\Enum\NotificationUrlTypeEnum;
use Tests\Support\Middleware\Enum\ProductDetailedContentTypeEnum;
use Tests\Support\Middleware\Enum\SosActivityIdEnum;
use Tests\Support\Middleware\Enum\SosTypeEnum;
use Tests\Support\Middleware\Enum\StoryPageBodyBackgroundImageTypeEnum;
use Tests\Support\Middleware\Enum\StoryPageBodyImageTypeEnum;
use Tests\Support\Middleware\Enum\StoryPageBodyTypeEnum;
use Tests\Support\Middleware\Enum\StoryStatusEnum;

class StructFactoryAPI2
{
    //@todo закомментированы поля, которые вовращаются с ошибками
    static function InsuranceGroup(): _struct
    {
        return new _object([
            'object_name' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'object_type' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'insurance_group_category_list' => new _property_array(self::InsuranceGroupCategory(), false, false),
        ]);
    }

    static function InsuranceGroupCategory(): _struct
    {
        return new _object([
            'insurance_category' => new _property_object(self::InsuranceCategory(), false),
            'insurance_list' => new _property_array(self::InsuranceShort(), false, false),
            'sos_activity' => new _property_object(self::SosActivity(), true),
        ]);
    }

    static function InsuranceCategory(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: InsuranceCategoryTypeEnum::class),
        ]);
    }

    static function InsuranceShort(): _struct
    {
        return new _object([
            'id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'start_date' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'end_date' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'renew_available' => new _property_simple(_simpleType::BOOL, false),
            'renew_type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: true, possibleValues: InsuranceRenewTypeEnum::class),
            'description' => new _property_simple(_simpleType::STRING, true),
            'event_report_type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: true, possibleValues: InsuranceEventReportTypeEnum::class),
            'label' => new _property_simple(_simpleType::STRING, true),
            'type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: InsuranceTypeEnum::class),
            'warning' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function SosActivity(): _object
    {
        return new _object([
            'id' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: SosActivityIdEnum::class),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'sos_phone_list' => new _property_array(self::SosPhone(), false, true),
            'insurance_id_list' => new _property_array(new _simple(_simpleType::STRING_NOT_EMPTY, false), false, true),
        ]);
    }

    static function SosPhone(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'phone' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'ip_id' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function Sos(): _struct
    {
        return new _object([
            'insurance_category' => new _property_object(self::InsuranceCategory(), true),
            'sos_phone' => new _property_object(self::SosPhone(), true),
            'type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: SosTypeEnum::class),
            'is_active' => new _property_simple(_simpleType::BOOL, false),
            'insurance_count' => new _property_simple(_simpleType::INT, false),
            'instruction_list' => new _property_array(self::Instruction(), false, false),
            'sos_activity_list' => new _property_array(self::SosActivity(), false, false),
        ]);
    }

    static function Sos_Insurance_Category(): _struct
    {
        $sos = self::Sos();

        $sos->updateField('insurance_category', new _property_object(self::InsuranceCategory(), false));

        return $sos;
    }

    static function Sos_Sos_Phone(): _struct
    {
        $sos = self::Sos();

        $sos->updateField('sos_phone', new _property_object(self::SosPhone(), false),);

        return $sos;
    }

    static function Instruction(): _struct
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'insurance_category_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'last_modified' => new _property_simple(_simpleType::INT, false),
            'steps' => new _property_array(self::InstructionStep(), false, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'short_description' => new _property_simple(_simpleType::STRING, false),
            'full_description' => new _property_simple(_simpleType::STRING, false),
        ]);
    }

    static function InstructionStep(): _struct
    {
        return new _object([
            'sort_number' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'full_description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function WebimVisitor(): _struct
    {
        return new _object([
            'fields' => new _property_object(self::WebimVisitorFields(), false),
            'hash' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function WebimVisitorFields(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'display_name' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'phone' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'email' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'info' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'segment' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function EventReportNs(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'date' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'insurance_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'status_id' => new _property_simple(simpleType: _simpleType::STRING, nullAllowed: true, possibleValues: EventReportNsStatusEnum::class),
            'status' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'status_description' => new _property_simple(_simpleType::STRING, true),
            'event' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'photo_cnt' => new _property_simple(_simpleType::INT, false),
            'is_opened' => new _property_simple(_simpleType::BOOL, false),
            'allow_attach_optional' => new _property_simple(_simpleType::BOOL, false),
            'allow_change_payout' => new _property_simple(_simpleType::BOOL, false),
            'bik' => new _property_simple(_simpleType::STRING, true),
            'account_number' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function LoyaltyBlock(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'image_url' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function DmsGaranteeLetter(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'clinic_title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'issued_datetime' => new _property_simple(_simpleType::INT, false),
            'status' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: DMSGaranteeLetterStatusEnum::class),
            'status_text' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'expiration_description' => new _property_simple(_simpleType::STRING, true),
            'download_url' => new _property_simple(_simpleType::STRING, true),

        ]);
    }

    static function DmsFranchiseChangeProgramPerson(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'first_name' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'last_name' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'patronymic' => new _property_simple(_simpleType::STRING, true),
            'has_program_pdf' => new _property_simple(_simpleType::BOOL, false),
            'has_clinics_pdf' => new _property_simple(_simpleType::BOOL, false),
            'is_checked' => new _property_simple(_simpleType::BOOL, false),
            'is_readonly' => new _property_simple(_simpleType::BOOL, false),
        ]);
    }

    static function PropertyOnOffInsurance(): _object
    {
        return new _object([
            'insurance_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'active_protection_list' => new _property_array(self::PropertyOnOffProtection(), false, true),
        ]);
    }

    static function PropertyOnOffProtection(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'start_date' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'end_date' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'days' => new _property_simple(_simpleType::INT, false),
            'status' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: ActiveProtectionStatusEnum::class),
            'policy_url' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function PropertyOnOffPurchaseItem(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'price' => new _property_simple(_simpleType::FLOAT, false),
            'days' => new _property_simple(_simpleType::INT, false),
            'success_text' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'contract_url' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'insurance_url' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function DmsFranchiseBillNotAgreeReason(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function DmsFranchiseBillService(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'corp_full_name' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'mt_date' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'service_name' => new _property_simple(_simpleType::STRING, false),
            'quantity' => new _property_simple(_simpleType::FLOAT, false),
            'sum_franch' => new _property_simple(_simpleType::FLOAT, false),
            'franchise' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'to_pay_value' => new _property_simple(_simpleType::FLOAT, false),
        ]);
    }

    static function NotificationNew(): _object
    {
        return new _object([
            'notification_id' => new _property_simple(_simpleType::INT, false),
            'datetime_created' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'status' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false, possibleValues: NotificationStatusEnum::class),
            'action' => new _property_object(self::Action(), true),
        ]);
    }

    static function Action(): _object
    {
        return new _object([
            'action_title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'action_type' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'action_info' => new _property_object(self::ActionInfo(), true)
        ]);
    }

    static function ActionInfo(): _object
    {
        return new _object([]);
    }

    static function NotificationNew_insurance(): _object
    {
        $notification = self::NotificationNew();

        $notification->updateField('action', new _property_object(self::Action_insurance(), true));

        return $notification;
    }

    static function Action_insurance(): _object
    {
        $action = self::Action();

        $action->updateField('action_info', new _property_object(self::ActionInfo_insurance(), true));

        return $action;
    }

    static function ActionInfo_insurance(): _object
    {
        $actionInfo = self::ActionInfo();

        $actionInfo->updateField('insurance_id', new _property_simple(_simpleType::STRING_NOT_EMPTY, false));

        return $actionInfo;
    }

    static function NotificationNew_dms_appointment_offline_mw(): _object
    {
        $notification = self::NotificationNew();

        $notification->updateField('action', new _property_object(self::Action_dms_appointment_offline_mw(), true));

        return $notification;
    }

    static function Action_dms_appointment_offline_mw(): _object
    {
        $action = self::Action();

        $action->updateField('action_info', new _property_object(self::ActionInfo_dms_appointment_offline_mw(), true));

        return $action;
    }

    static function ActionInfo_dms_appointment_offline_mw(): _object
    {
        return new _object([
            'insurance_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
//            'appointment_id' => new _property_simple(_simpleType::INT, false),
            'appointment_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),

        ]);
    }

    static function NotificationNew_dms_appointment_online(): _object
    {
        $notification = self::NotificationNew();

        $notification->updateField('action', new _property_object(self::Action_dms_appointment_online(), true));

        return $notification;
    }

    static function Action_dms_appointment_online(): _object
    {
        $action = self::Action();

        $action->updateField('action_info', new _property_object(self::ActionInfo_dms_appointment_online(), true));

        return $action;
    }

    static function ActionInfo_dms_appointment_online(): _object
    {
        return new _object([
            'insurance_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
//            'doctor_visit_id' => new _property_simple(_simpleType::INT, false),
            'doctor_visit_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),

        ]);
    }

    static function NotificationNew_url(): _object
    {
        $notification = self::NotificationNew();

        $notification->updateField('action', new _property_object(self::Action_url(), true));

        return $notification;
    }

    static function Action_url(): _object
    {
        $action = self::Action();

        $action->updateField('action_info', new _property_object(self::ActionInfo_url(), true));

        return $action;
    }

    static function ActionInfo_url(): _object
    {
        return new _object([
            'url' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'type' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false, possibleValues: NotificationUrlTypeEnum::class),
        ]);
    }

    static function NotificationNew_telemed(): _object
    {
        $notification = self::NotificationNew();

        $notification->updateField('action', new _property_object(self::Action(), true));

        return $notification;
    }

    static function NotificationNew_event_report_osago(): _object
    {
        $notification = self::NotificationNew();

        $notification->updateField('action', new _property_object(self::Action_event_report_osago(), true));

        return $notification;
    }

    static function Action_event_report_osago(): _object
    {
        $action = self::Action();

        $action->updateField('action_info', new _property_object(self::ActionInfo_event_report_osago(), true));

        return $action;
    }

    static function ActionInfo_event_report_osago(): _object
    {
        return new _object([
            'insurance_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'event_report_id' => new _property_simple(_simpleType::INT, false),
//            'event_report_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),

        ]);
    }

    static function NotificationNew_event_report_kasko(): _object
    {
        $notification = self::NotificationNew();

        $notification->updateField('action', new _property_object(self::Action_event_report_kasko(), true));

        return $notification;
    }

    static function Action_event_report_kasko(): _object
    {
        $action = self::Action();

        $action->updateField('action_info', new _property_object(self::ActionInfo_event_report_kasko(), true));

        return $action;
    }

    static function ActionInfo_event_report_kasko(): _object
    {
        return new _object([
            'insurance_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
//            'event_report_id' => new _property_simple(_simpleType::INT, false),
            'event_report_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),

        ]);
    }

    static function NotificationNew_loyalty(): _object
    {
        $notification = self::NotificationNew();

        $notification->updateField('action', new _property_object(self::Action(), true));

        return $notification;
    }

    static function NotificationNew_property_prolongation(): _object
    {
        $notification = self::NotificationNew();

        $notification->updateField('action', new _property_object(self::Action_property_prolongation(), true));

        return $notification;
    }

    static function Action_property_prolongation(): _object
    {
        $action = self::Action();

        $action->updateField('action_info', new _property_object(self::ActionInfo_property_prolongation(), true));

        return $action;
    }

    static function ActionInfo_property_prolongation(): _object
    {
        return new _object([
            'insurance_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function File(): _object
    {
        return new _object([
            'file_id' => new _property_simple(_simpleType::INT, false),
            'datetime_created' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'title_extension' => new _property_simple(_simpleType::STRING, true),
            'guid' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'status' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false, possibleValues: FileStatusEnum::class),
            'size' => new _property_simple(_simpleType::INT, false),
            'mime_type' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function ApiStatus(): _object
    {
        return new _object([
            'status' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false, possibleValues: ApiStatusEnum::class),
            'title' => new _property_simple(_simpleType::STRING, true),
            'description' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function DmsCompensationFormData(): _object
    {
        return new _object([
            'plan_info' => new _property_object(self::PlanInfo(), false),
            'insurer' => new _property_object(self::Insurer(), false),
            'insured_list' => new _property_array(self::Insured(), false, false),
            'medical_service_list' => new _property_array(self::MedicalService(), false, false),
            'currency_list' => new _property_array(self::Currency(), false, false),
            'popular_bank_list' => new _property_array(self::Bank(), false, false),
            'files_info' => new _property_object(self::FilesInfo(), false),
            'passport' => new _property_object(self::Passport(), true),
            'refund_requisites' => new _property_object(self::RefundRequisites(), true),
            'additional_personal_info' => new _property_object(self::AdditionalPersonalInfo(), true),
        ]);
    }

    static function PlanInfo(): _object
    {
        return new _object([
            'details' => new _property_object(self::PlanDetails(), true),
            'step_list' => new _property_array(self::Step(), false, false),
            'what_to_do_info' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function PlanDetails(): _object
    {
        return new _object([
            //@todo по спеке пустое поле. Уточнить
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'pdf_url' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function Step(): _object
    {
        return new _object([
            'number' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function Insurer(): _object
    {
        return new _object([
            'full_name' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'birthday' => new _property_simple(_simpleType::STRING_NOT_EMPTY, true),
            'policy_number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'tab_number' => new _property_simple(_simpleType::STRING, true),
            'phone' => new _property_object(self::Phone(), true),
            'email' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function Phone(): _object
    {
        return new _object([
            'human_readable' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'plain' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'ip_id' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function Insured(): _object
    {
        return new _object([
            'full_name' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'birthday' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'policy_number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function MedicalService(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'value' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'user_input_required' => new _property_simple(_simpleType::BOOL, false),
        ]);
    }

    static function Currency(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'value' => new _property_simple(_simpleType::STRING, false),
            'user_input_required' => new _property_simple(_simpleType::BOOL, false),
        ]);
    }

    static function Bank(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'bik' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'corr_number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function FilesInfo(): _object
    {
        return new _object([
            'file_type_list' => new _property_array(self::FileType(), false, false),
            'files_limit' => new _property_simple(_simpleType::INT, false),
        ]);
    }

    static function FileType(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'file_group_list' => new _property_array(self::FileGroups(), false, false),
        ]);
    }

    static function FileGroups(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'label' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'file_item_list' => new _property_array(self::FileItem(), false, false),
        ]);
    }

    static function FileItem(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'value' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'is_required' => new _property_simple(_simpleType::BOOL, false),
            'is_multiselect_allowed' => new _property_simple(_simpleType::BOOL, false),
        ]);
    }

    static function Passport(): _object
    {
        return new _object([
            'series' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'issue_place' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'issue_date' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'birth_place' => new _property_simple(_simpleType::STRING, false),
            'citizenship' => new _property_simple(_simpleType::STRING, false),
        ]);
    }

    static function RefundRequisites(): _object
    {
        return new _object([
            'bank' => new _property_object(self::Bank(), true),
            'account_number' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function AdditionalPersonalInfo(): _object
    {
        return new _object([
            'citizen_type' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false, possibleValues: CitizenTypeEnum::class),
            'snils_number' => new _property_simple(_simpleType::STRING, true),
            'inn_number' => new _property_simple(_simpleType::STRING, true),
            'migration_card_number' => new _property_simple(_simpleType::STRING, true),
            'residential_address' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    /**
     * Функция которая возвращает два объекта рекурсивно вложенные друг в друга
     * @return _object[] Массив [Card, CardGroup]
     */
    protected static function CardAndCardGroup(): array
    {
        $cardGroup = new _object([
            'card_group_id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'type' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false, possibleValues: CardGroupTypeEnum::class),
        ]);

        $card = new _object([
            'card_id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'image' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'type' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false, possibleValues: CardTypeEnum::class),
            'link' => new _property_simple(_simpleType::STRING, true),
        ]);

        $card->updateField('card_group', new _property_object($cardGroup, true));
        $cardGroup->updateField('card_list', new _property_array($card, false, false));

        return [$card, $cardGroup];
    }

    static function CardGroup(): _object
    {
        [$card, $cardGroup] = self::CardAndCardGroup();
        return $cardGroup;
    }

    static function Card(): _object
    {
        [$card, $cardGroup] = self::CardAndCardGroup();
        return $card;
    }

    static function Story(): _object
    {
        return new _object([
            'story_id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'title_color' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'preview' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'page_list' => new _property_array(self::StoryPage(), false, false),
            'status' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false, possibleValues: StoryStatusEnum::class),
        ]);
    }

    static function StoryPage(): _object
    {
        return new _object([
            'page_id' => new _property_simple(_simpleType::INT, false),
            'time' => new _property_simple(_simpleType::FLOAT, false),
            'body_type' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false, possibleValues: StoryPageBodyTypeEnum::class),
            'body' => new _property_object(self::StoryPageBody(), true),
            'body_html' => new _property_simple(_simpleType::STRING, true),
            'cross_color' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'stripe_color' => new _property_simple(_simpleType::STRING, true),
            'button' => new _property_object(self::Button(), true),
        ]);
    }

    static function StoryPageBody(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING, true),
            'title_color' => new _property_simple(_simpleType::STRING, true),
            'text' => new _property_simple(_simpleType::STRING, true),
            'text_color' => new _property_simple(_simpleType::STRING, true),
            'image_type' => new _property_simple(simpleType: _simpleType::STRING, nullAllowed: true, possibleValues: StoryPageBodyImageTypeEnum::class),
            'image' => new _property_simple(_simpleType::STRING, true),
            'background_image_type' => new _property_simple(simpleType: _simpleType::STRING, nullAllowed: true, possibleValues: StoryPageBodyBackgroundImageTypeEnum::class),
            'background_image' => new _property_simple(_simpleType::STRING, true),
            'background_color' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function Button(): _object
    {
        return new _object([
            'button_text_color' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'button_color' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'action' => new _property_object(self::Action_url(), false),
        ]);
    }

    static function InsuranceCategoryWithProduct(): _object
    {
        return new _object([
            'category_id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'product_list' => new _property_array(self::Product(), false, false),
            'show_in_filters' => new _property_simple(_simpleType::BOOL, false),
        ]);
    }

    static function Product(): _object
    {
        return new _object([
            'product_id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'text' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'tag_list' => new _property_array(self::ProductTag(), false, true),
            'image' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            //@todo передалать. Могут быть объекты различной структуры
            'detailed_content' =>  new _property_array(self::ProductDetailedContent(), false, false),
        ]);
    }

    static function ProductTag(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'title_color' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'background_color' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    //@todo ниже объекты, которые можно передавать для формирования структуры проверки
    static function ProductDetailedContent(): _object
    {
        return new _object([
            'type' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false, possibleValues: ProductDetailedContentTypeEnum::class),
            'data' => new _property_object(self::DataContentTitle(), false),
        ]);
    }

    static function ProductDetailedContentTitle(): _object
    {
        $productDetailedContent = self::ProductDetailedContent();

        $productDetailedContent->updateField('data', new _property_object(self::DataContentTitle(), false));

        return $productDetailedContent;
    }

    static function ProductDetailedContentImage(): _object
    {
        $productDetailedContent = self::ProductDetailedContent();

        $productDetailedContent->updateField('data', new _property_object(self::DataContentImage(), false));

        return $productDetailedContent;
    }

    static function ProductDetailedContentButton(): _object
    {
        $productDetailedContent = self::ProductDetailedContent();

        $productDetailedContent->updateField('data', new _property_object(self::DataContentButton(), false));

        return $productDetailedContent;
    }

    static function ProductDetailedContentLinkedText(): _object
    {
        $productDetailedContent = self::ProductDetailedContent();

        $productDetailedContent->updateField('data', new _property_object(self::DataContentLinkedText(), false));

        return $productDetailedContent;
    }

    static function ProductDetailedContentListWithCheckmark(): _object
    {
        $productDetailedContent = self::ProductDetailedContent();

        $productDetailedContent->updateField('data', new _property_object(self::DataContentListWithCheckmark(), false, false));

        return $productDetailedContent;
    }

    static function DataContentTitle(): _object
    {
        return new _object([
            'text' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function DataContentLinkedText(): _object
    {
        return new _object([
            'text' => new _property_object(self::LinkedText(), false),
        ]);
    }

    static function DataContentListWithCheckmark(): _object
    {
        return new _object([
            'text_list' => new _property_array(new _simple(_simpleType::STRING_NOT_EMPTY, false), false, false),
        ]);
    }

    static function DataContentImage(): _object
    {
        return new _object([
            'image' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function DataContentButton(): _object
    {
        return new _object([
            'button_text_color' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'button_color' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'action' => new _property_object(self::Action_url(), false),
        ]);
    }

    static function LinkedText(): _object
    {
        return new _object([
            'text' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'links' => new _property_array(self::LinkedTextLink(), false, false),
        ]);
    }

    static function LinkedTextLink(): _object
    {
        return new _object([
            'text' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'link' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }
}