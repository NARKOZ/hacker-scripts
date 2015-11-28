<?php

/**
 * Parent class for usage resources that expose a single date, eg 'Today', 'ThisMonth', etc
 * @author Kevin Burke <kevin@twilio.com>
 * @license  http://creativecommons.org/licenses/MIT/ MIT
 * @link     http://pear.php.net/package/Services_Twilio
 */
class Services_Twilio_TimeRangeResource extends Services_Twilio_UsageResource {

    /**
     * Return a UsageRecord corresponding to the given category.
     *
     * @param string $category The category of usage to retrieve. For a full 
     *      list of valid categories, please see the documentation at 
     *      http://www.twilio.com/docs/api/rest/usage-records#usage-all-categories
     * @return Services_Twilio_Rest_UsageRecord
     * @throws Services_Twilio_RestException
     */
    public function getCategory($category) {
        $page = $this->getPage(0, 1, array(
            'Category' => $category,
        ));
        $items = $page->getItems();
        if (!is_array($items) || count($items) === 0) {
            throw new Services_Twilio_RestException(
                400, "Usage record data is unformattable.");
        }
        return $items[0];
    }
}
