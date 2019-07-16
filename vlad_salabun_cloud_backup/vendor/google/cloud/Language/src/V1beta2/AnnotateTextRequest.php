<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/language/v1beta2/language_service.proto

namespace Google\Cloud\Language\V1beta2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * The request message for the text annotation API, which can perform multiple
 * analysis types (sentiment, entities, and syntax) in one call.
 *
 * Generated from protobuf message <code>google.cloud.language.v1beta2.AnnotateTextRequest</code>
 */
class AnnotateTextRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Input document.
     *
     * Generated from protobuf field <code>.google.cloud.language.v1beta2.Document document = 1;</code>
     */
    private $document = null;
    /**
     * The enabled features.
     *
     * Generated from protobuf field <code>.google.cloud.language.v1beta2.AnnotateTextRequest.Features features = 2;</code>
     */
    private $features = null;
    /**
     * The encoding type used by the API to calculate offsets.
     *
     * Generated from protobuf field <code>.google.cloud.language.v1beta2.EncodingType encoding_type = 3;</code>
     */
    private $encoding_type = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Google\Cloud\Language\V1beta2\Document $document
     *           Input document.
     *     @type \Google\Cloud\Language\V1beta2\AnnotateTextRequest\Features $features
     *           The enabled features.
     *     @type int $encoding_type
     *           The encoding type used by the API to calculate offsets.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Language\V1Beta2\LanguageService::initOnce();
        parent::__construct($data);
    }

    /**
     * Input document.
     *
     * Generated from protobuf field <code>.google.cloud.language.v1beta2.Document document = 1;</code>
     * @return \Google\Cloud\Language\V1beta2\Document
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Input document.
     *
     * Generated from protobuf field <code>.google.cloud.language.v1beta2.Document document = 1;</code>
     * @param \Google\Cloud\Language\V1beta2\Document $var
     * @return $this
     */
    public function setDocument($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Language\V1beta2\Document::class);
        $this->document = $var;

        return $this;
    }

    /**
     * The enabled features.
     *
     * Generated from protobuf field <code>.google.cloud.language.v1beta2.AnnotateTextRequest.Features features = 2;</code>
     * @return \Google\Cloud\Language\V1beta2\AnnotateTextRequest\Features
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * The enabled features.
     *
     * Generated from protobuf field <code>.google.cloud.language.v1beta2.AnnotateTextRequest.Features features = 2;</code>
     * @param \Google\Cloud\Language\V1beta2\AnnotateTextRequest\Features $var
     * @return $this
     */
    public function setFeatures($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Language\V1beta2\AnnotateTextRequest_Features::class);
        $this->features = $var;

        return $this;
    }

    /**
     * The encoding type used by the API to calculate offsets.
     *
     * Generated from protobuf field <code>.google.cloud.language.v1beta2.EncodingType encoding_type = 3;</code>
     * @return int
     */
    public function getEncodingType()
    {
        return $this->encoding_type;
    }

    /**
     * The encoding type used by the API to calculate offsets.
     *
     * Generated from protobuf field <code>.google.cloud.language.v1beta2.EncodingType encoding_type = 3;</code>
     * @param int $var
     * @return $this
     */
    public function setEncodingType($var)
    {
        GPBUtil::checkEnum($var, \Google\Cloud\Language\V1beta2\EncodingType::class);
        $this->encoding_type = $var;

        return $this;
    }

}

