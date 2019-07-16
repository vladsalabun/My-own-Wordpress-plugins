<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/redis/v1beta1/cloud_redis.proto

namespace Google\Cloud\Redis\V1beta1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request for [Failover][google.cloud.redis.v1beta1.CloudRedis.FailoverInstance].
 *
 * Generated from protobuf message <code>google.cloud.redis.v1beta1.FailoverInstanceRequest</code>
 */
class FailoverInstanceRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. Redis instance resource name using the form:
     *     `projects/{project_id}/locations/{location_id}/instances/{instance_id}`
     * where `location_id` refers to a GCP region.
     *
     * Generated from protobuf field <code>string name = 1;</code>
     */
    private $name = '';
    /**
     * Optional. Available data protection modes that the user can choose. If it's
     * unspecified, data protection mode will be LIMITED_DATA_LOSS by default.
     *
     * Generated from protobuf field <code>.google.cloud.redis.v1beta1.FailoverInstanceRequest.DataProtectionMode data_protection_mode = 2;</code>
     */
    private $data_protection_mode = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *           Required. Redis instance resource name using the form:
     *               `projects/{project_id}/locations/{location_id}/instances/{instance_id}`
     *           where `location_id` refers to a GCP region.
     *     @type int $data_protection_mode
     *           Optional. Available data protection modes that the user can choose. If it's
     *           unspecified, data protection mode will be LIMITED_DATA_LOSS by default.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Redis\V1Beta1\CloudRedis::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. Redis instance resource name using the form:
     *     `projects/{project_id}/locations/{location_id}/instances/{instance_id}`
     * where `location_id` refers to a GCP region.
     *
     * Generated from protobuf field <code>string name = 1;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Required. Redis instance resource name using the form:
     *     `projects/{project_id}/locations/{location_id}/instances/{instance_id}`
     * where `location_id` refers to a GCP region.
     *
     * Generated from protobuf field <code>string name = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

    /**
     * Optional. Available data protection modes that the user can choose. If it's
     * unspecified, data protection mode will be LIMITED_DATA_LOSS by default.
     *
     * Generated from protobuf field <code>.google.cloud.redis.v1beta1.FailoverInstanceRequest.DataProtectionMode data_protection_mode = 2;</code>
     * @return int
     */
    public function getDataProtectionMode()
    {
        return $this->data_protection_mode;
    }

    /**
     * Optional. Available data protection modes that the user can choose. If it's
     * unspecified, data protection mode will be LIMITED_DATA_LOSS by default.
     *
     * Generated from protobuf field <code>.google.cloud.redis.v1beta1.FailoverInstanceRequest.DataProtectionMode data_protection_mode = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setDataProtectionMode($var)
    {
        GPBUtil::checkEnum($var, \Google\Cloud\Redis\V1beta1\FailoverInstanceRequest_DataProtectionMode::class);
        $this->data_protection_mode = $var;

        return $this;
    }

}

