<?php

namespace CSSValidator;

class Options
{
    /**
     * Output format
     *          Triggers the various outputs formats of the validator. If unset,
     *          the usual Web html format will be sent. If set to soap12,
     *          the SOAP1.2 interface will be triggered.
     *
     * @var string
     */
    protected $output = 'soap12';

    /**
     * Warning level
     *           Default value is '1', and value could one of these :
     *           <ul>
     *             <li>2</li> all warning messages
     *             <li>1</li> normal report
     *             <li>0</li> most important warning messages
     *             <li>no</li> none messages
     *           </ul>
     *
     * @var string
     */
    protected $warning = '1';

    /**
     * Profile
     *           Default value is 'css21', and value could one of these :
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
     *           </ul>
     *
     * @var string
     */
    protected $profile = 'css21';

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
     *              </ul>
     *
     * @var string
     */
    protected $usermedium = 'all';

    /**
     * Language used for response messages
     *        Default value is 'en', and value could one of these :
     *        en, fr, ja, es, zh-cn, nl, de
     *
     * @var string
     */
    protected $lang = 'en';

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     *
     * @return Options
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param string $output
     *
     * @return Options
     */
    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @return string
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param string $profile
     *
     * @return Options
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsermedium()
    {
        return $this->usermedium;
    }

    /**
     * @param string $usermedium
     *
     * @return Options
     */
    public function setUsermedium($usermedium)
    {
        $this->usermedium = $usermedium;

        return $this;
    }

    /**
     * @return string
     */
    public function getWarning()
    {
        return $this->warning;
    }

    /**
     * @param string $warning
     *
     * @return Options
     */
    public function setWarning($warning)
    {
        $this->warning = $warning;

        return $this;
    }

    /**
     * @return array
     */
    public function buildOptions()
    {
        return array(
            'output' => $this->getOutput(),
            'profile' => $this->getProfile(),
            'warning' => $this->getWarning(),
            'usermedium' => $this->getUsermedium(),
            'lang' => $this->getLang(),
        );
    }
}
