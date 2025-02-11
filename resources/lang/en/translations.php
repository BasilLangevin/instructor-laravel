<?php

return [
    'retry_message' => <<<'EOT'
        Your response did not match the required format and validation rules. The following errors were encountered:

        :errors

        Please correct these issues and provide a revised response that aligns with the expected format.
        EOT,
];
