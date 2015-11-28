<?php

class Services_Twilio_Rest_Calls
    extends Services_Twilio_ListResource
{

    function init($client, $uri)
    {
        $this->setupSubresources(
            'feedback_summary'
        );
    }

    public static function isApplicationSid($value)
    {
        return strlen($value) == 34
            && !(strpos($value, "AP") === false);
    }

    public function create($from, $to, $url, array $params = array())
    {

        $params["To"] = $to;
        $params["From"] = $from;

        if (self::isApplicationSid($url)) {
            $params["ApplicationSid"] = $url;
        } else {
            $params["Url"] = $url;
        }

        return parent::_create($params);
    }

    /**
     * Create a feedback for a call.
     *
     * @param $callSid
     * @param $qualityScore
     * @param array $issue
     * @return Services_Twilio_Rest_Feedback
     */
    public function createFeedback($callSid, $qualityScore, array $issue = array())
    {
        $params["QualityScore"] = $qualityScore;
        $params["Issue"] = $issue;

        $feedbackUri = $this->uri . '/' . $callSid . '/Feedback';

        $response = $this->client->createData($feedbackUri, $params);
        return new Services_Twilio_Rest_Feedback($this->client, $feedbackUri, $response);
    }

    /**
     * Delete a feedback for a call.
     *
     * @param $callSid
     */
    public function deleteFeedback($callSid)
    {
        $feedbackUri = $this->uri . '/' . $callSid . '/Feedback';
        $this->client->deleteData($feedbackUri);
    }

    /**
     * Get a feedback for a call.
     *
     * @param $callSid
     * @return Services_Twilio_Rest_Feedback
     */
    public function getFeedback($callSid)
    {
        $feedbackUri = $this->uri . '/' . $callSid . '/Feedback';
        $response = $this->client->retrieveData($feedbackUri);
        return new Services_Twilio_Rest_Feedback($this->client, $feedbackUri, $response);
    }
}
