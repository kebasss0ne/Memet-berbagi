<?php

return [
    'allowed_ips' => array_values(array_filter(
        array_map('trim', explode(',', env('ADMIN_ALLOWED_IPS', '')))
    )),
];
