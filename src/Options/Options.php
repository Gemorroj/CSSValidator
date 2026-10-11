<?php

namespace CSSValidator\Options;

class Options
{
    /**
     * Warning level. Default is {@see WarningType::Normal}.
     */
    private string $warning = WarningType::Normal->value;

    /**
     * Profile. Default is {@see ProfileType::Css3}.
     */
    private string $profile = ProfileType::Css3->value;

    /**
     * User medium. Default is {@see UserMediumType::All}.
     */
    private string $userMedium = UserMediumType::All->value;

    /**
     * Language used for response messages.
     *
     * Default value is 'en', and value could one of these: en, fr, ja, es, zh-cn, nl, de.
     */
    private string $lang = 'en';

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

    /**
     * @throws \InvalidArgumentException when an unknown profile name is given
     */
    public function setProfile(ProfileType|string $profile): self
    {
        if (\is_string($profile)) {
            $profile = ProfileType::tryFrom($profile) ?? throw new \InvalidArgumentException(\sprintf('Unknown profile "%s".', $profile));
        }

        $this->profile = $profile->value;

        return $this;
    }

    public function getUserMedium(): string
    {
        return $this->userMedium;
    }

    /**
     * @throws \InvalidArgumentException when an unknown user medium name is given
     */
    public function setUserMedium(UserMediumType|string $userMedium): self
    {
        if (\is_string($userMedium)) {
            $userMedium = UserMediumType::tryFrom($userMedium) ?? throw new \InvalidArgumentException(\sprintf('Unknown user medium "%s".', $userMedium));
        }

        $this->userMedium = $userMedium->value;

        return $this;
    }

    public function getWarning(): string
    {
        return $this->warning;
    }

    /**
     * @throws \InvalidArgumentException when an unknown warning level is given
     */
    public function setWarning(WarningType|string $warning): self
    {
        if (\is_string($warning)) {
            $warning = WarningType::tryFrom($warning) ?? throw new \InvalidArgumentException(\sprintf('Unknown warning level "%s".', $warning));
        }

        $this->warning = $warning->value;

        return $this;
    }

    /**
     * @return array{profile: string, warning: string, usermedium: string, lang: string}
     */
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
