<?php
/**
 * @package alchemis
 */
class app_domain_CmToProjectRel
{
    const CATEGORY    = '3';
    const FILTER_TYPE = '3';
    const CLICKED     = 'clicked';
    const VIEWED      = 'viewed';

    /**
     *
     */
    protected $_tagName;

    /**
     * Filter ID
     * @var int
     */
    protected $_filterId;

    /**
     * Post array
     *
     * Array of post ids in order to compare again email > contact > post
     *
     * post initiative id => post id key, value pairs
     *
     * @var array
     */
    protected $_posts = array();

    /**
     * Get filter ID
     *
     * @return int
     */
    public function getFilterId()
    {
        return $this->_filterId;
    }

    /**
     * Create Tag
     *
     * @param string $tagName Tag Name
     *
     * @return array
     */
    public function createTags($tagName)
    {
        $this->_tagName = $tagName;
        $this->_createFilter($tagName);
        $this->_buildPostInitiativeArray($tagName);

        return array(
            self::CLICKED => $this->_createTag($tagName, self::CLICKED),
            self::VIEWED  => $this->_createTag($tagName, self::VIEWED)
        );
    }

    /**
     * Attach post to tag
     *
     * @param string|array $email Email address(es)
     * @param int          $tagId Tag ID
     *
     * @return bool|array email => bool pairs or false if tag could not be found
     */
    public function attachPostInitiativeViewed($email, $tagName)
    {
        $this->createTags($tagName);
        $tagViewed = app_domain_Tag::findByValue($tagName . ' ' . self::VIEWED);
        $this->attachPostInitiatives($email, $tagViewed);
    }

    /**
     * Attach post to tag
     *
     * @param string|array $email Email address(es)
     * @param int          $tagId Tag ID
     *
     * @return bool|array email => bool pairs or false if tag could not be found
     */
    public function attachPostInitiativeClicked($email, $tagName)
    {
        $this->createTags($tagName);
        $tagClicked = app_domain_Tag::findByValue($tagName . ' ' . self::CLICKED);
        $this->attachPostInitiatives($email, $tagClicked);
    }

    /**
     * Attach post initiatives to tag
     *
     * Retrieve post initiatives that are associated with the mailer of the same
     * name as the Campaign Monitor campaign
     *
     * @return void
     */
    public function attachPostInitiatives($emailAddress, $tag)
    {
        $contacts = app_domain_Contact::findByContactEmail($emailAddress);
        if ($contacts)
        {
            foreach ($contacts as $contact)
            {
              $postInitiatives = app_domain_PostInitiative::findByPostId($contact->getPostId());
              foreach ($postInitiatives as $postInitiative) {
                if ($postInitiative && !$postInitiative->hasTag($tag->getId()))
                {
                  $tag->setParentDomainObject($postInitiative);
                  $tag->markDirty();
                  $tag->commit();
                  
                  $noteText = $tag->getValue();
                  
                  $note = new app_domain_PostInitiativeNote();
                  $note->setPostInitiativeId($postInitiative->getId());
                  $note->setCreatedAt(date('Y-m-d H:i:s'));
                  $note->setCreatedBy(1);
                  $note->setNote($noteText);
                  $note->commit();
                }
              }
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Create tag
     *
     * @param string $tagName Tag name
     * @param string $type    Clicked or viewed
     */
    protected function _createTag($tagName, $type)
    {
        $currentTag = app_domain_Tag::findByValue($tagName . ' ' . $type);
        if ($currentTag)
        {
            return $currentTag->getId();
        }

        $tag = new app_domain_Tag();
        $tag->setValue($tagName . ' ' . $type);
        $tag->setCategoryId(self::CATEGORY);
        $tag->commit();
        return $tag->getId();
    }

    /**
     *
     */
    protected function _buildPostInitiativeArray($tagName)
    {
        if (!isset($this->_posts[$tagName]))
        {
            $this->_posts[$tagName] = array();
            $mailerItems = array();
            $mailer      = app_domain_Mailer::findByName($tagName);

            if ($mailer)
            {
                $mailerItems = app_domain_MailerItem::findByMailerId($mailer->getId());
            }
            if (count($mailerItems) > 0)
            {
                foreach ($mailerItems as $mailerItem)
                {
                    $postInitiative = app_domain_PostInitiative::find($mailerItem['post_initiative_id']);
                    if ($postInitiative)
                    {
                        $this->_posts[$tagName][$postInitiative->getPostId()] = $postInitiative->getId();
                    }
                }
            }
        }
    }

    /**
     * Create filter
     *
     * @return bool
     */
    protected function _createFilter($filterName)
    {
        $currentFilter = app_domain_Filter::findByName($filterName);
        if ($currentFilter)
        {
            return;
        }
        $filter        = new app_domain_Filter;
        $filterBuilder = new app_domain_FilterBuilder;

        $filter->setName($filterName);
        $filter->setTypeId(self::FILTER_TYPE);
        $filter->setCreatedAt(date('Y-m-d H:i:s'));
        $filter->setResultsFormat('Site and posts');
        $filter->setCreatedBy(1);
        $filter->setIsReportSource(false);
        $filter->setReportParameterDescription('');
        $filter->commit();

        $view = array(
            'bracket_open'       => '',
            'where_table'        => 'post initiative',
            'where_field'        => 'project ref',
            'where_operator'     => 'equals',
            'where_data'         => $filterName . ' ' . self::CLICKED,
            'where_data_display' => $filterName . ' ' . self::CLICKED,
            'bracket_close'      => '',
            'concatenator'       => 'or'
        );

        $click = array(
            'bracket_open'       => '',
            'where_table'        => 'post initiative',
            'where_field'        => 'project ref',
            'where_operator'     => 'equals',
            'where_data'         => $filterName . ' ' . self::VIEWED,
            'where_data_display' => $filterName . ' ' . self::VIEWED,
            'bracket_close'      => '',
            'concatenator'       => 'and'
        );

        $include = array(
            (object) $view,
            (object) $click
        );

        $filterBuilder->saveLineItems(
            $include,
            $filter->getId(),
            'include'
        );
        $this->_filterId = $filter->getId();
    }

}