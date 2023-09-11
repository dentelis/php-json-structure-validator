<?php

namespace EntelisTeam\Validator\Structure;

use EntelisTeam\Validator\Enum\_simpleType;
use Tests\Support\Middleware\Enum\AccountAdditionsEnum;
use Tests\Support\Middleware\Enum\AppointmentAVISClinicTypeEnum;
use Tests\Support\Middleware\Enum\AppointmentHasOnlineRegEnum;
use Tests\Support\Middleware\Enum\AppointmentTypeEnum;
use Tests\Support\Middleware\Enum\DayOfWeekEnum;
use Tests\Support\Middleware\Enum\DMSBillHighlightedTypeEnum;
use Tests\Support\Middleware\Enum\DoctorIntervalIsFreeEnum;
use Tests\Support\Middleware\Enum\EventInfoTypeEnum;
use Tests\Support\Middleware\Enum\InsuranceAdditionsEnum;
use Tests\Support\Middleware\Enum\InsuranceCategoryTypeEnum;
use Tests\Support\Middleware\Enum\InsuranceFieldTypeEnum;
use Tests\Support\Middleware\Enum\InsuranceSearchPolicyRequestStateEnum;
use Tests\Support\Middleware\Enum\InsuranceTypeEnum;
use Tests\Support\Middleware\Enum\LoyaltyOperationIconTypeEnum;
use Tests\Support\Middleware\Enum\LoyaltyOperationOperationTypeEnum;
use Tests\Support\Middleware\Enum\LoyaltyOperationStatusEnum;
use Tests\Support\Middleware\Enum\LoyaltyOperationTypeEnum;
use Tests\Support\Middleware\Enum\NotificationTypeEnum;
use Tests\Support\Middleware\Enum\RenewStatusOsagoEnum;
use Tests\Support\Middleware\Enum\ResolutionTypeEnum;
use Tests\Support\Middleware\Enum\RiskCategoryTypeEnum;
use Tests\Support\Middleware\Enum\RiskDataIsRequiredEnum;
use Tests\Support\Middleware\Enum\RiskDataTypeEnum;
use Tests\Support\Middleware\Enum\SosActivityIdEnum;
use Tests\Support\Middleware\Enum\TelematicAccessEnum;
use Tests\Support\Middleware\Enum\UserEnum;


class StructFactory
{
    //@todo закомментированы поля, которые вовращаются с ошибками
    static function Insurance(): _object
    {
        return new _object(
            [
                'id' => new _property_simple(_simpleType::INT, false),
                'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
                'contract_id' => new _property_simple(_simpleType::STRING, true),
                'contract_number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
                'insurance_series' => new _property_simple(_simpleType::STRING, true),
                'insurance_number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, true),
                'start_date' => new _property_simple(_simpleType::INT, false),
                'end_date' => new _property_simple(_simpleType::INT, false),
                'description' => new _property_simple(_simpleType::STRING, true),
                'InsurancePremium' => new _property_simple(_simpleType::STRING, true),
                'owner_participants' => new _property_array(self::InsuranceParticipant(), false, true),
                'insurer_participants' => new _property_array(self::InsuranceParticipant(), true, false),
                'insured_participants' => new _property_array(self::InsuranceParticipant(), true, true),
                'benefit_participants' => new _property_array(self::InsuranceParticipant(), true, false),
                'drivers' => new _property_array(self::InsuranceParticipant(), true, true),
                'car' => new _property_object(self::Car(), true),
                'trip_segments' => new _property_array(self::TripSegment(), true, false),
                'product_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
                'renew_available' => new _property_simple(_simpleType::BOOL, true),
                'renew_status' => new _property_simple(_simpleType::STRING, true),
                'renew_url' => new _property_simple(_simpleType::STRING, true),
                'renew_insurance_id' => new _property_simple(_simpleType::STRING, true),
                'field_group_list' => new _property_array(self::InsuranceFieldGroup(), false, false),
                'insured_object' => new _property_simple(_simpleType::STRING, false),
                'emergency_phone' => new _property_object(self::Phone(), false),
                'sos_activities' => new _property_array(arrayItemType: new _simple(_simpleType::INT, false), nullAllowed: false, emptyAllowed: true, possibleValues: SosActivityIdEnum::class),
                'clinic_id_list' => new _property_array(new _simple(_simpleType::STRING, false), true, true),
                'access_clinic_phone' => new _property_simple(_simpleType::BOOL, true),
                'type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: InsuranceTypeEnum::class),
                'archive_date' => new _property_simple(_simpleType::INT, false),
                'file_link' => new _property_simple(_simpleType::STRING, true),
                'help_file' => new _property_simple(_simpleType::STRING, true),
                'passbook_available' => new _property_simple(_simpleType::BOOL, false),
                'passbook_available_online' => new _property_simple(_simpleType::BOOL, false),
                'insurance_id_outer' => new _property_simple(_simpleType::STRING, true),
                'insurance_id_mobile_deeplink' => new _property_simple(_simpleType::STRING, true),
                'access_telemed' => new _property_simple(_simpleType::BOOL, false),
                'is_insurer' => new _property_simple(_simpleType::BOOL, false),
                'is_child' => new _property_simple(_simpleType::BOOL, true),
                'company' => new _property_simple(_simpleType::STRING, true),
                'child_phone' => new _property_object(self::Phone(), true),
                'need_accept_franchise' => new _property_simple(_simpleType::BOOL, false),
                'franchise_text' => new _property_simple(_simpleType::STRING, true),
                'show_bills' => new _property_simple(_simpleType::BOOL, false),
                'has_unpaid_bills' => new _property_simple(_simpleType::BOOL, false),
                'bills' => new _property_array(self::DmsBill(), false, true),
                'show_garantee_letters' => new _property_simple(_simpleType::BOOL, false),
                'is_change_franch_program_available' => new _property_simple(_simpleType::BOOL, false),
                'has_vzr_bonus' => new _property_simple(_simpleType::BOOL, false),
                'additions' => new _property_array(arrayItemType: new _simple(_simpleType::STRING, false), nullAllowed: false, emptyAllowed: true, possibleValues: InsuranceAdditionsEnum::class),
            ]
        );
    }

    static function Insurance_Kasko(): _struct
    {
        $insurance = self::Insurance();

        $insurance->updateField('benefit_participants', new _property_array(self::InsuranceParticipant(), false, true));
        $insurance->updateField('car', new _property_object(self::Car(), false));
        $insurance->updateField('drivers', new _property_array(self::InsuranceParticipant(), false, false));

        return $insurance;
    }

    static function Insurance_Osago(): _struct
    {
        $insurance = self::Insurance();

//        $insurance->updateField('drivers', new _property_array(self::InsuranceParticipant(), true, true));
//        $insurance->updateField('renew_status', new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: RenewStatusOsagoEnum::class));
        $insurance->updateField('renew_status', new _property_simple(simpleType: _simpleType::INT, nullAllowed: true, possibleValues: RenewStatusOsagoEnum::class));
//        $insurance->updateField('renew_insurance_id', new _property_simple(_simpleType::STRING_NOT_EMPTY, false));
        $insurance->updateField('renew_insurance_id', new _property_simple(_simpleType::STRING_NOT_EMPTY, true));

        return $insurance;
    }

    static function Insurance_Trip(): _struct
    {
        $insurance = self::Insurance();

        $insurance->updateField('trip_segments', new _property_array(self::TripSegment(), false, false));

        return $insurance;
    }

    static function InsuranceParticipant(): _struct
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, true),
            'full_name' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'first_name' => new _property_simple(_simpleType::STRING, true),
            'last_name' => new _property_simple(_simpleType::STRING, true),
            'patronymic' => new _property_simple(_simpleType::STRING, true),
            'birth_date' => new _property_simple(_simpleType::INT, true),
            'birth_date_iso' => new _property_simple(_simpleType::STRING, true),
            'sex' => new _property_simple(_simpleType::STRING, true),
            'contact_information' => new _property_simple(_simpleType::STRING, true),
            'address' => new _property_array(self::Address(), true, false),
            'full_address' => new _property_simple(_simpleType::STRING, true),
            'email' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function Address(): _struct
    {
        return new _object([
            'index' => new _property_simple(_simpleType::STRING, true),
            'country' => new _property_simple(_simpleType::STRING, true),
            'city' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'street_house_apartment' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function Car(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING, true),
            'reg_number' => new _property_simple(_simpleType::STRING, true),
            'power' => new _property_simple(_simpleType::STRING, true),
            'vin' => new _property_simple(_simpleType::STRING, true),
            'issue_year' => new _property_simple(_simpleType::STRING, true),
            'cert_seria' => new _property_simple(_simpleType::STRING, true),
            'cert_number' => new _property_simple(_simpleType::STRING, true),
            'key_count' => new _property_simple(_simpleType::STRING, true),
            'passport_seria' => new _property_simple(_simpleType::STRING, true),
            'passport_number' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function TripSegment(): _struct
    {
        return new _object([
            'number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'departure' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'arrival' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function InsuranceFieldGroup(): _struct
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'fields' => new _property_array(self::InsuranceField(), false, false),
        ]);
    }

    static function InsuranceField(): _struct
    {
        return new _object([
            'type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: InsuranceFieldTypeEnum::class),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'text' => new _property_simple(_simpleType::STRING, false),
            'coordinate' => new _property_array(self::Coordinate(), true, false),
        ]);
    }

    static function Coordinate(): _object
    {
        return new _object([
            'longitude' => new _property_simple(_simpleType::FLOAT, false),
            'latitude' => new _property_simple(_simpleType::FLOAT, false),
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

    static function DmsBill(): _struct
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'bill_number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'bill_info' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'status' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
//            'date' => new _property_simple(_simpleType::INT, false),
            'date' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'amount' => new _property_simple(_simpleType::FLOAT, false),
            'description' => new _property_simple(_simpleType::STRING, false),
            'is_payment_needed' => new _property_simple(_simpleType::BOOL, false),
            'can_be_group_paid' => new _property_simple(_simpleType::BOOL, false),
            'highlighted_type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: DMSBillHighlightedTypeEnum::class),
            'date_paid' => new _property_simple(_simpleType::STRING, true),
            'user_name' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'can_create_not_agreed' => new _property_simple(_simpleType::BOOL, false),
        ]);
    }

    static function InsuranceShort(): _struct
    {
        return new _object([
            'contract_number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'start_date' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'end_date' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'product_code' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'product_title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ], true);
    }

    static function Appointment(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'avis_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'phone' => new _property_object(self::Phone(), false),
            'date' => new _property_simple(_simpleType::INT, false),
            'reason_text' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'clinic_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'insurance_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'clinic' => new _property_object(self::Clinic(), true)
        ]);
    }

    static function Clinic(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'address' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'coordinate' => new _property_object(self::Coordinate(), false),
            'service_hours' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'services' => new _property_array(new _simple(_simpleType::STRING_NOT_EMPTY, false), false, false),
            'service_list' => new _property_array(self::ClinicService(), false, false),
            'phones' => new _property_array(self::Phone(), false, false),
            'city_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'priority' => new _property_simple(_simpleType::INT, false),
            'web_address' => new _property_simple(_simpleType::STRING, true),
            'metro_distance' => new _property_simple(_simpleType::INT, true),
            'has_online_reg' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: AppointmentHasOnlineRegEnum::class),
            'appointment_type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: AppointmentTypeEnum::class),
            'doctor_specialities' => new _property_array(self::DoctorSpeciality(), true, true),
            'has_services_with_franchise' => new _property_simple(_simpleType::BOOL, false),
        ]);
    }

    static function ClinicService(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'has_franchise' => new _property_simple(_simpleType::BOOL, false),
            'franchise_size' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function DoctorSpeciality(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'description' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function ClinicSpeciality(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'user_input_required' => new _property_simple(_simpleType::BOOL, false),
        ]);
    }

    static function InsurancesCategory(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'terms_url' => new _property_simple(_simpleType::STRING, true),
            'sort_priority' => new _property_simple(_simpleType::INT, false),
            'days_left' => new _property_simple(_simpleType::INT, false),
            'product_id_list' => new _property_array(new _simple(_simpleType::STRING_NOT_EMPTY, false), false, false),
            'type' => new _property_simple(_simpleType::INT, false, possibleValues: InsuranceCategoryTypeEnum::class),
            'subtitle' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false),
        ]);
    }

    static function InsurancesProduct(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'event_type_list' => new _property_array(self::EventType(), false, true),
        ]);
    }

    static function EventType(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'full_description' => new _property_simple(_simpleType::STRING, false),
            'info' => new _property_array(self::EventTypeInfo(), true, true),
            'documents_list' => new _property_object(self::DocumentsList(), true),
            'documents_list_optional' => new _property_object(self::DocumentsList(), true),
        ]);
    }

    static function EventTypeInfo(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: EventInfoTypeEnum::class),
            'value' => new _property_simple(_simpleType::STRING, true),
            'available_value_list' => new _property_array(new _simple(_simpleType::STRING_NOT_EMPTY, false), true, false),
            'is_mandatory' => new _property_simple(_simpleType::BOOL, false, false),
            'default_value' => new _property_simple(_simpleType::STRING, true),
            'value_list' => new _property_array(new _simple(_simpleType::STRING_NOT_EMPTY, false), true, false),
            'placeholder' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function DocumentsList(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'documents' => new _property_array(new _simple(_simpleType::STRING_NOT_EMPTY, false), false, false),
            'full_description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function Product(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'phone' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function InsuranceDealer(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function Money(): _object
    {
        return new _object([
            'currency' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'amount' => new _property_simple(_simpleType::INT, false),
        ]);
    }

    static function EventReport(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'date' => new _property_simple(_simpleType::INT, false),
            'sent_date' => new _property_simple(_simpleType::INT, false),
            'full_description' => new _property_simple(_simpleType::STRING, true),
            'files' => new _property_array(self::Preview(), true, true),
            'type' => new _property_array(self::EventType(), false, false),
            'coordinate' => new _property_object(self::Coordinate(), true),
            'insurance_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),

        ]);
    }

    static function Preview(): _object
    {
        return new _object([
            'small_image_url' => new _property_simple(_simpleType::STRING, true),
            'big_image_url' => new _property_simple(_simpleType::STRING, true),
            'url' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function EventReportAuto(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'date' => new _property_simple(_simpleType::INT, false),
            'sent_date' => new _property_simple(_simpleType::INT, false),
            'statuses' => new _property_array(self::EventStatus(), false, false),
            'is_opened' => new _property_simple(_simpleType::BOOL, false),
            'full_description' => new _property_simple(_simpleType::STRING, true),
            'files' => new _property_array(self::Preview(), false, true),
            'type' => new _property_object(self::EventType(), false),
            'coordinate' => new _property_object(self::Coordinate(), true),
            'address' => new _property_simple(_simpleType::STRING, true),
            'requisites' => new _property_simple(_simpleType::STRING, true),
            'insurance_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'files_uploaded' => new _property_simple(_simpleType::BOOL, false),
        ]);
    }

    static function EventStatus(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'sort_number' => new _property_simple(_simpleType::INT, false),
            'date' => new _property_simple(_simpleType::INT, true),
            'decision' => new _property_object(self::EventDecision(), true),
            'passed' => new _property_simple(_simpleType::BOOL, false),
            'stoa' => new _property_object(self::STOA(), true),
            'image_url' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'short_description' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function EventDecision(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'sum' => new _property_object(self::Money(), true),
            'number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'url' => new _property_simple(_simpleType::STRING, false),
            'resolution' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: ResolutionTypeEnum::class),
        ]);
    }

    static function STOA(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'address' => new _property_simple(_simpleType::STRING, true),
            'coordinate' => new _property_object(self::Coordinate(), false),
            'service_hours' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'dealer' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'phone_list' => new _property_array(self::Phone(), false, false),
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
            'status' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'is_opened' => new _property_simple(_simpleType::BOOL, false),
        ]);
    }

    static function InsuranceProductLink(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function Office(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'address' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'coordinate' => new _property_object(self::Coordinate(), false),
            'phones' => new _property_array(self::Phone(), false, true),
            'service_hours' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'services' => new _property_array(new _simple(_simpleType::STRING_NOT_EMPTY, false), false, true),
            'city_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'payment_methods' => new _property_simple(_simpleType::STRING, true),
            'campaigns' => new _property_simple(_simpleType::STRING, true),
            'card_pay' => new _property_simple(_simpleType::BOOL, false),
            'make_selling' => new _property_simple(_simpleType::BOOL, false),
            'make_damage_claim' => new _property_simple(_simpleType::BOOL, false),
            'make_osago_claim' => new _property_simple(_simpleType::BOOL, false),
            'make_telematics_install' => new _property_simple(_simpleType::BOOL, false),
            'damage_claim_text' => new _property_simple(_simpleType::STRING, true),
            'osago_claim_text' => new _property_simple(_simpleType::STRING, true),
            'advert_text' => new _property_simple(_simpleType::STRING, true),
            'additional_contacts' => new _property_simple(_simpleType::STRING, true),
            'special_conditions' => new _property_simple(_simpleType::STRING, true),
            'metro' => new _property_array(new _simple(_simpleType::STRING_NOT_EMPTY, false), false, true),
            'timetable' => new _property_array(self::OfficeTimetable(), false, false),
            'special_timetable' => new _property_array(self::OfficeSpecialTimetable(), false, true),
        ]);
    }

    static function OfficeTimetable(): _object
    {
        return new _object([
            'day' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false, possibleValues: DayOfWeekEnum::class),
            'is_working' => new _property_simple(_simpleType::BOOL, false),
            'office_hours' => new _property_object(self::OfficeTimetableHours(), true),
        ]);
    }

    static function OfficeTimetableHours(): _object
    {
        return new _object([
            'start_time' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'close_time' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'break_start_time' => new _property_simple(_simpleType::STRING, true),
            'break_end_time' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function OfficeSpecialTimetable(): _object
    {
        return new _object([
            'day' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'is_working' => new _property_simple(_simpleType::BOOL, false),
            'office_hours' => new _property_object(self::OfficeTimetableHours(), true),
        ]);
    }

    static function QuestionCategory(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'question_group_list' => new _property_array(self::QuestionGroup(), false, false),
        ]);
    }

    static function QuestionGroup(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'question_list' => new _property_array(self::Question(), false, false),
        ]);
    }

    static function Question(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'is_frequent' => new _property_simple(_simpleType::BOOL, false),
            'question_text' => new _property_simple(_simpleType::STRING, false),
            'answer' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'answer_html' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'last_modified' => new _property_simple(_simpleType::INT, false),
        ]);
    }

    static function Risk(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'nomatch_id_list' => new _property_array(new _simple(_simpleType::INT, false), false, false),
            'risk_category_list' => new _property_array(self::RiskCategory(), false, false),
        ]);
    }

    static function RiskCategory(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING, true),
            'type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: RiskCategoryTypeEnum::class),
            'dependence' => new _property_object(self::RiskDataDependency(), true),
            'risk_data_list' => new _property_array(self::RiskData(), false, false),
        ]);
    }

    static function RiskDataDependency(): _object
    {
        return new _object([
            'risk_data_id_checkbox' => new _property_simple(_simpleType::INT, true),
            'risk_data_option_id' => new _property_simple(_simpleType::INT, true),
        ]);
    }

    static function RiskData(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: RiskDataTypeEnum::class),
            'title' => new _property_simple(_simpleType::STRING, true),
            'title_options' => new _property_simple(_simpleType::STRING, true),
            'is_required' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: RiskDataIsRequiredEnum::class),
            'risk_data_option_list' => new _property_array(self::RiskDataOption(), true, false),
            'available_symbols' => new _property_array(new _simple(_simpleType::STRING_NOT_EMPTY, false), true, false),
            'max_length' => new _property_simple(_simpleType::INT, true),
        ]);
    }

    static function RiskDataOption(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function Instruction(): _object
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

    static function InstructionStep(): _object
    {
        return new _object([
            'sort_number' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'full_description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function Campaign(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'annotation' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'begin_date' => new _property_simple(_simpleType::INT, false),
            'end_date' => new _property_simple(_simpleType::INT, false),
            'full_description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'image_url' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'phone' => new _property_object(self::Phone(), false),
            'url' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function CityWithMetro(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'longitude' => new _property_simple(_simpleType::FLOAT, false),
            'latitude' => new _property_simple(_simpleType::FLOAT, false),
            'radius' => new _property_simple(_simpleType::FLOAT, false),
        ]);
    }

    static function MetroStation(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'longitude' => new _property_simple(_simpleType::FLOAT, false),
            'latitude' => new _property_simple(_simpleType::FLOAT, false),
            'clinic_list' => new _property_array(self::Clinic(), false, true),
        ]);
    }

    static function City(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function Loyalty(): _object
    {
        return new _object([
            'points_amount' => new _property_simple(_simpleType::FLOAT, false),
            'points_added' => new _property_simple(_simpleType::FLOAT, false),
            'points_spent' => new _property_simple(_simpleType::FLOAT, false),
            'status' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'status_description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'next_status' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'next_status_money' => new _property_simple(_simpleType::FLOAT, false),
            'next_status_description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'hotline_description' => new _property_simple(_simpleType::STRING, true),
            'hotline_phone' => new _property_object(self::Phone(), true),
            'last_operations' => new _property_array(self::LoyaltyOperation(), false, true),
            'insurance_deeplink_types' => new _property_array(self::InsuranceDeeplinkType(), false, false),
            'operations_cnt' => new _property_simple(_simpleType::INT, false),
        ]);
    }

    static function LoyaltyOperation(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'product_id' => new _property_simple(_simpleType::INT, true),
            'category_id' => new _property_simple(_simpleType::INT, true),
            'category_type' => new _property_simple(_simpleType::INT, true),
            'insurance_deeplink_type' => new _property_simple(_simpleType::INT, true),
            'type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: LoyaltyOperationTypeEnum::class),
            'operation_type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: true, possibleValues: LoyaltyOperationOperationTypeEnum::class),
            'amount' => new _property_simple(_simpleType::FLOAT, false),
            'description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'date' => new _property_simple(_simpleType::INT, false),
            'status' => new _property_simple(_simpleType::STRING, true),
            'status_id' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: true, possibleValues: LoyaltyOperationStatusEnum::class),
            'contract_number' => new _property_simple(_simpleType::STRING, true),
            'icon_type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: true, possibleValues: LoyaltyOperationIconTypeEnum::class),
        ]);
    }

    static function InsuranceDeeplinkType(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'category_id' => new _property_simple(_simpleType::INT, false),
        ]);
    }

    static function DoctorWithSchedule(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'doctor_speciality' => new _property_object(self::DoctorSpeciality(), false),
            'photo_url' => new _property_simple(_simpleType::STRING, true),
            'experience_years' => new _property_simple(_simpleType::INT, true),
            'experience_description' => new _property_simple(_simpleType::STRING, true),
            'interval_dates' => new _property_array(self::DoctorIntervalDate(), false, false),
        ]);
    }

    static function DoctorIntervalDate(): _object
    {
        return new _object([
            'date' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'intervals' => new _property_array(self::DoctorInterval(), false, false),
        ]);
    }

    static function DoctorInterval(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'date' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'start_time' => new _property_simple(_simpleType::INT, false),
            'end_time' => new _property_simple(_simpleType::INT, false),
            'is_free' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: DoctorIntervalIsFreeEnum::class),
        ]);
    }

    static function DoctorVisit(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'clinic' => new _property_object(self::Clinic(), false),
            'doctor' => new _property_object(self::Doctor(), false),
            'interval' => new _property_object(self::DoctorInterval(), false),
            'insurance_id' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'alert' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function Doctor(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'doctor_speciality' => new _property_object(self::DoctorSpeciality(), false),
            'photo_url' => new _property_simple(_simpleType::STRING, true),
            'experience_years' => new _property_simple(_simpleType::INT, true),
            'experience_description' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function InsuranceSearchPolicyProduct(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'suggest' => new _property_simple(_simpleType::STRING, true),
            'example' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function InsuranceSearchPolicyRequest(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'insurance_number' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'image_url' => new _property_simple(_simpleType::STRING, true),
            'issue_date' => new _property_simple(_simpleType::STRING, true),
            'request_datetime' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'state' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false, possibleValues: InsuranceSearchPolicyRequestStateEnum::class),
            'planned_date' => new _property_simple(_simpleType::STRING, true),
            'planned_date_min' => new _property_simple(_simpleType::STRING, true),
            'type' => new _property_simple(_simpleType::INT, false),
        ]);
    }

    static function Notification(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'annotation' => new _property_simple(_simpleType::STRING, false),
            'full_text' => new _property_simple(_simpleType::STRING, false),
            'date' => new _property_simple(_simpleType::INT, false),
            'important' => new _property_simple(_simpleType::BOOL, false),
            'insurance_id' => new _property_simple(_simpleType::STRING, true),
            'stoa' => new _property_object(self::STOA(), true),
            'appointment_id' => new _property_simple(_simpleType::STRING, true),
            'doctor_visit_id' => new _property_simple(_simpleType::INT, true),
            'field_list' => new _property_array(self::NotificationField(), true, false),
            'phone' => new _property_object(self::Phone(), true),
            'user_request_date' => new _property_simple(_simpleType::INT, true),
            'event_number' => new _property_simple(_simpleType::STRING, true),
            'type' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: NotificationTypeEnum::class),
            'is_read' => new _property_simple(_simpleType::BOOL, false),
            'event_report_id' => new _property_simple(_simpleType::STRING, true),
            'photo_types' => new _property_array(new _simple(_simpleType::STRING, false), true, false),
            'url' => new _property_simple(_simpleType::STRING, true),
            'target' => new _property_simple(_simpleType::INT, true),
        ]);
    }

    static function NotificationField(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING, false),
            'value' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function Account(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'first_name' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'last_name' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'patronymic' => new _property_simple(_simpleType::STRING, true),
            'phone' => new _property_object(self::Phone(), false),
            'birth_date' => new _property_simple(_simpleType::INT, false),
            'email' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'telematic_access' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: TelematicAccessEnum::class),
            'unconfirmed_phone' => new _property_object(self::Phone(), true),
            'unconfirmed_email' => new _property_simple(_simpleType::STRING, true),
            'is_demo' => new _property_simple(simpleType: _simpleType::INT, nullAllowed: false, possibleValues: UserEnum::class),
            'additions' => new _property_array(new _simple(_simpleType::STRING_NOT_EMPTY, false), false, false, possibleValues: AccountAdditionsEnum::class),
        ]);
    }

    static function AppointmentAVIS(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'date' => new _property_simple(_simpleType::INT, false),
            'full_date' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'clinic_type' => new _property_simple(simpleType: _simpleType::STRING_NOT_EMPTY, nullAllowed: false, possibleValues: AppointmentAVISClinicTypeEnum::class),
            'clinic' => new _property_object(self::Clinic(), true),
            'clinic_javis ' => new _property_object(self::ClinicJavis(), true),
            'can_be_cancelled' => new _property_simple(_simpleType::BOOL, false),
            'can_be_recreated' => new _property_simple(_simpleType::BOOL, false),
            'doctor' => new _property_simple(_simpleType::STRING, true),
            'description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function Session(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'access_token' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);
    }

    static function ClinicJavis(): _object
    {
        return new _object([
            'id' => new _property_simple(_simpleType::INT, false),
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'address' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'coordinate' => new _property_object(self::Coordinate(), false),
            'service_hours' => new _property_simple(_simpleType::STRING, true),
            'phone' => new _property_object(self::Phone(), true),
            'web_address' => new _property_simple(_simpleType::STRING, true),
        ]);
    }

    static function InsuranceProlongationEstateRisk(): _object
    {
        return new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'description' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'limit' => new _property_simple(_simpleType::FLOAT, false),
        ]);
    }
}