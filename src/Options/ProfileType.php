<?php

namespace CSSValidator\Options;

enum ProfileType: string
{
    /**
     * No profile.
     */
    case None = 'none';

    /**
     * CSS level 1.
     */
    case Css1 = 'css1';

    /**
     * CSS level 2.
     */
    case Css2 = 'css2';

    /**
     * CSS level 2.1.
     */
    case Css21 = 'css21';

    /**
     * CSS level 3.
     */
    case Css3 = 'css3';

    /**
     * SVG.
     */
    case Svg = 'svg';

    /**
     * SVG Basic.
     */
    case SvgBasic = 'svgbasic';

    /**
     * SVG Tiny.
     */
    case SvgTiny = 'svgtiny';

    /**
     * Mobile.
     */
    case Mobile = 'mobile';

    /**
     * ATSC TV.
     */
    case AtscTv = 'atsc-tv';

    /**
     * TV.
     */
    case Tv = 'tv';
}
