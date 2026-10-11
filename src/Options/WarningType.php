<?php

namespace CSSValidator\Options;

enum WarningType: string
{
    /**
     * All warning messages.
     */
    case All = '2';

    /**
     * Normal report.
     */
    case Normal = '1';

    /**
     * Only the most important warning messages.
     */
    case Important = '0';

    /**
     * No warning messages.
     */
    case None = 'no';
}
