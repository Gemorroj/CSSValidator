<?php

namespace CSSValidator;

class Options
{
    public const PROFILE_NONE = 'none';
    public const PROFILE_CSS1 = 'css1';
    public const PROFILE_CSS2 = 'css2';
    public const PROFILE_CSS21 = 'css21';
    public const PROFILE_CSS3 = 'css3';
    public const PROFILE_SVG = 'svg';
    public const PROFILE_SVG_BASIC = 'svgbasic';
    public const PROFILE_SVG_TINY = 'svgtiny';
    public const PROFILE_MOBILE = 'mobile';
    public const PROFILE_ATSC_TV = 'atsc-tv';
    public const PROFILE_TV = 'tv';

    public const USER_MEDIUM_ALL = 'all';
    public const USER_MEDIUM_AURAL = 'aural';
    public const USER_MEDIUM_BRAILLE = 'braille';
    public const USER_MEDIUM_EMBOSSED = 'embossed';
    public const USER_MEDIUM_HANDHELD = 'handheld';
    public const USER_MEDIUM_PRINT = 'print';
    public const USER_MEDIUM_PROJECTION = 'projection';
    public const USER_MEDIUM_SCREEN = 'screen';
    public const USER_MEDIUM_TTY = 'tty';
    public const USER_MEDIUM_TV = 'tv';
    public const USER_MEDIUM_PRESENTATION = 'presentation';

    public const WARNING_ALL = '2';
    public const WARNING_NORMAL = '1';
    public const WARNING_IMPORTANT = '0';
    public const WARNING_NONE = 'no';

    /**
     * Warning level
     *           Default value is '1', and value could one of these :
     *           <ul>
     *             <li>2</li> all warning messages
     *             <li>1</li> normal report
     *             <li>0</li> most important warning messages
     *             <li>no</li> none messages
     *           </ul>.
     *
     * @var string
     */
    private $warning = self::WARNING_NORMAL;

    /**
     * Profile
     *           Default value is 'css3', and value could one of these :
     *           <ul>
     *             <li>none</li> none profile
     *             <li>css1</li> CSS level 1
     *             <li>css2</li> CSS level 2
     *             <li>css21</li> CSS level 2.1
     *             <li>css3</li> CSS level 3
     *             <li>svg</li> SVG
     *             <li>svgbasic</li> SVG Basic
     *             <li>svgtiny</li> SVG Tiny
     *             <li>mobile</li> Mobile
     *             <li>atsc-tv</li> ATSC TV
     *             <li>tv</li> TV
     *           </ul>.
     *
     * @var string
     */
    private $profile = self::PROFILE_CSS3;

    /**
     * User medium
     *              Default value is 'all', and value could one of these :
     *              <ul>
     *                <li>all</li>
     *                <li>aural</li>
     *                <li>braille</li>
     *                <li>embossed</li>
     *                <li>handheld</li>
     *                <li>print</li>
     *                <li>projection</li>
     *                <li>screen</li>
     *                <li>tty</li>
     *                <li>tv</li>
     *                <li>presentation</li>
     *              </ul>.
     *
     * @var string
     */
    private $userMedium = self::USER_MEDIUM_ALL;

    /**
     * Language used for response messages
     *        Default value is 'en', and value could one of these :
     *        en, fr, ja, es, zh-cn, nl, de.
     *
     * @var string
     */
    private $lang = 'en';

    public function getLang(): string
    {
        return $this->lang;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getProfile(): string
    {
        return $this->profile;
    }

    public function setProfile(string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getUserMedium(): string
    {
        return $this->userMedium;
    }

    public function setUserMedium($userMedium): self
    {
        $this->userMedium = $userMedium;

        return $this;
    }

    public function getWarning(): string
    {
        return $this->warning;
    }

    public function setWarning(string $warning): self
    {
        $this->warning = $warning;

        return $this;
    }

    public function buildOptions(): array
    {
        return [
            'profile' => $this->getProfile(),
            'warning' => $this->getWarning(),
            'usermedium' => $this->getUserMedium(),
            'lang' => $this->getLang(),
        ];
    }
}
