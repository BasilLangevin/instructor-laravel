<?php

namespace BasilLangevin\Instructor\Enums;

enum Role: string
{
    case System = 'system';
    case User = 'user';
    case Assistant = 'assistant';
}
