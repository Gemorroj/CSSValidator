<?php

namespace CSSValidator\Options;

enum UserMediumType: string
{
    case All = 'all';
    case Aural = 'aural';
    case Braille = 'braille';
    case Embossed = 'embossed';
    case Handheld = 'handheld';
    case Print = 'print';
    case Projection = 'projection';
    case Screen = 'screen';
    case Tty = 'tty';
    case Tv = 'tv';
    case Presentation = 'presentation';
}
