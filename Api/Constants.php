<?php

namespace BroCode\AmqpMonitor\Api;

class Constants
{
    const CONFIG_GENERAL_API_ENDPOINT = 'brocode_amqp/general/management_api_endpoint';
    const CONFIG_GENERAL_API_USERNAME= 'brocode_amqp/general/basic_username';
    const CONFIG_GENERAL_API_PASSWORD = 'brocode_amqp/general/basic_password';

    const CONFIG_NOTIFICATIONS_ENABLED = 'brocode_amqp/notifications/enabled';

    const CONFIG_NOTIFICATIONS_THRESHOLD_MINMESSAGECOUNT = 'brocode_amqp/notifications/threshold/min_message_count';
    const CONFIG_NOTIFICATIONS_THRESHOLD_MINGROWTHPERCENTAGE = 'brocode_amqp/notifications/threshold/min_growth_percentage';
    const CONFIG_NOTIFICATIONS_THRESHOLD_ALERTMESSAGECOUNT = 'brocode_amqp/notifications/threshold/alert_message_count';

    const CONFIG_NOTIFICATIONS_EMAIL_ENABLED = 'brocode_amqp/notifications/email/enabled';
    const CONFIG_NOTIFICATIONS_EMAIL_RECIPIENTS = 'brocode_amqp/notifications/email/recipient';
}
