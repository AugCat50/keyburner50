<?php
/**
 * Модель для обслуживагния сущности пользовательского текста и его статистики
 */
namespace DomainObjectAssembler\DomainModel;

class UserTextModel extends DomainModel
{
    /**
     * В случае отсутствия значения, значение по умолчанию задаётся в UserTextIdentityObject
     */
    private $user_id;
    private $user_themes;
    private $theme_name;
    private $text;
    private $statistics;
    private $statistics_best;

    public function __construct(int $id, int $user_id, int $user_themes, string $name, string $text, string $statistics = '', string $statistics_best = '')
    {
        $this->user_id         = $user_id;
        $this->user_themes     = $user_themes;
        $this->name            = $name;
        $this->text            = $text;
        $this->statistics      = $statistics;
        $this->statistics_best = $statistics_best;
        parent::__construct($id);
    }

    public function setUserId(int $user_id)
    {
        $this->user_id = $user_id;
        $this->markDirty();
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserThemes(string $user_themes)
    {
        $this->user_themes = $user_themes;
        $this->markDirty();
    }

    public function getUserThemes(): string
    {
        return $this->user_themes;
    }

    /**
     * Имя темы только для внутренних системных нужд. markDirty не требуется
     */
    public function setThemeName(string $theme_name)
    {
        $this->theme_name = $theme_name;
    }

    public function getThemeName(): string
    {
        return $this->theme_name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
        $this->markDirty();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setText(string $text)
    {
        $this->text = $text;
        $this->markDirty();
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getStatistics(): string
    {
        return $this->statistics;
    }

    public function setStatistics(string $statistics)
    {
        $this->statistics = $statistics;
        $this->markDirty(['statistics', 'getStatistics']);
    }

    public function getStatisticsBest():string
    {
        return $this->statistics_best;
    }

    public function setStatisticsBest(string $statistics_best)
    {
        $this->statistics_best = $statistics_best;
        $this->markDirty(['statistics_best', 'getStatisticsBest']);
    }

    public function getModelName(): string
    {
        return 'UserText';
    }
}
