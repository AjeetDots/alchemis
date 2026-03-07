<?php

/**
 * Defines the concrete subclasses of app_mapper_Collection.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

//require_once('app/domain.php');
require_once('app/domain/Collections.php');
require_once('app/mapper.php');
require_once('app/mapper/Collection.php');

/**
 * By extending app_domain_ActionCollection, we also extend Iterator.
 * @package Alchemis
 */
class app_mapper_ActionCollection extends app_mapper_Collection implements app_domain_ActionCollection
{
	public function add(app_domain_Action $action)
	{
		$this->doAdd($action);
	}
}

/**
 * By extending app_domain_CampaignCompanyDoNotCallCollection, we also extend Iterator.
 * @package Alchemis
 */
class app_mapper_CampaignCompanyDoNotCallCollection extends app_mapper_Collection implements app_domain_CampaignCompanyDoNotCallCollection
{
	public function add(app_domain_CampaignCompanyDoNotCall $campaign_company_do_not_call)
	{
		$this->doAdd($campaign_company_do_not_call);
	}
}

/**
 * By extending app_domain_CampaignCollection, we also extend Iterator.
 * @package Alchemis
 */
class app_mapper_CampaignCollection extends app_mapper_Collection implements app_domain_CampaignCollection
{
	public function add(app_domain_Campaign $campaign)
	{
		$this->doAdd($campaign);
	}
}

/**
 * By extending app_domain_CampaignDisciplineCollection, we also extend Iterator.
 * @package Alchemis
 */
class app_mapper_CampaignDisciplineCollection extends app_mapper_Collection implements app_domain_CampaignDisciplineCollection
{
	public function add(app_domain_CampaignDiscipline $campaign_discipline)
	{
		$this->doAdd($campaign_discipline);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_CampaignNbmCollection extends app_mapper_Collection implements app_domain_CampaignNbmCollection
{
	public function add(app_domain_CampaignNbm $campaign_nbm)
	{
		$this->doAdd($campaign_nbm);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_CampaignRegionCollection extends app_mapper_Collection implements app_domain_CampaignRegionCollection
{
	public function add(app_domain_CampaignRegion $campaign_region)
	{
		$this->doAdd($campaign_region);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_CampaignReportSummaryCollection extends app_mapper_Collection implements app_domain_CampaignReportSummaryCollection
{
	public function add(app_domain_CampaignReportSummary $campaign_report_summary)
	{
		$this->doAdd($campaign_report_summary);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_CampaignSectorCollection extends app_mapper_Collection implements app_domain_CampaignSectorCollection
{
	public function add(app_domain_CampaignSector $campaign_sector)
	{
		$this->doAdd($campaign_sector);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_CampaignTargetCollection extends app_mapper_Collection implements app_domain_CampaignTargetCollection
{
	public function add(app_domain_CampaignTarget $campaign_target)
	{
		$this->doAdd($campaign_target);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_CharacteristicCollection extends app_mapper_Collection implements app_domain_CharacteristicCollection
{
	public function add(app_domain_Characteristic $characteristic)
	{
		$this->doAdd($characteristic);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_CharacteristicElementCollection extends app_mapper_Collection implements app_domain_CharacteristicElementCollection
{
	public function add(app_domain_CharacteristicElement $element)
	{
		$this->doAdd($element);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_ClientCollection extends app_mapper_Collection implements app_domain_ClientCollection
{
	public function add(app_domain_Client $client)
	{
		$this->doAdd($client);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_CmCampaignCollection extends app_mapper_Collection implements app_domain_CmCampaignCollection
{
	public function add(app_domain_CmCampaign $cmCampaign)
	{
		$this->doAdd($cmCampaign);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_CommunicationCollection extends app_mapper_Collection implements app_domain_CommunicationCollection
{
	public function add(app_domain_Communication $communication)
	{
		$this->doAdd($communication);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_CommunicationAttachmentCollection extends app_mapper_Collection implements app_domain_CommunicationAttachmentCollection
{
	public function add(app_domain_CommunicationAttachment $communication_attachment)
	{
		$this->doAdd($communication_attachment);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_CompanyCollection extends app_mapper_Collection implements app_domain_CompanyCollection
{
	public function add(app_domain_Company $company)
	{
		$this->doAdd($company);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_CompanyNoteCollection extends app_mapper_Collection implements app_domain_CompanyNoteCollection
{
	public function add(app_domain_CompanyNote $company_note)
	{
		$this->doAdd($company_note);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_ContactCollection extends app_mapper_Collection implements app_domain_ContactCollection
{
	public function add(app_domain_Contact $contact)
	{
		$this->doAdd($contact);
	}
}

/**
 * @package Framework
 */
class app_mapper_DocumentCollection extends app_mapper_Collection implements app_domain_DocumentCollection
{
	public function add(app_domain_Document $document)
	{
		$this->doAdd($document);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_EventCollection extends app_mapper_Collection implements app_domain_EventCollection
{
	public function add(app_domain_Event $event)
	{
		$this->doAdd($event);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_FilterBuilderCollection extends app_mapper_Collection implements app_domain_FilterBuilderCollection
{
	public function add(app_domain_FilterBuilder $filter_builder)
	{
		$this->doAdd($filter_builder);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_FilterCollection extends app_mapper_Collection implements app_domain_FilterCollection
{
	public function add(app_domain_Filter $filter)
	{
		$this->doAdd($filter);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_FilterLineCollection extends app_mapper_Collection implements app_domain_FilterLineCollection
{
	public function add(app_domain_FilterLine $filter_line)
	{
		$this->doAdd($filter_line);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_ImportCompanyCollection extends app_mapper_Collection implements app_domain_ImportCompanyCollection
{
    public function add(app_domain_ImportCompany $import)
    {
        $this->doAdd($import);
    }
}

/**
 * @package Alchemis
 */
class app_mapper_ImportPostCollection extends app_mapper_Collection implements app_domain_ImportPostCollection
{
    public function add(app_domain_ImportPost $import)
    {
        $this->doAdd($import);
    }
}

/**
 * @package Alchemis
 */
class app_mapper_InformationRequestCollection extends app_mapper_Collection implements app_domain_InformationRequestCollection
{
	public function add(app_domain_InformationRequest $information_request)
	{
		$this->doAdd($information_request);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_MailerCollection extends app_mapper_Collection implements app_domain_MailerCollection
{
	public function add(app_domain_Mailer $mailer)
	{
		$this->doAdd($mailer);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_MailerItemCollection extends app_mapper_Collection implements app_domain_MailerItemCollection
{
	public function add(app_domain_MailerItem $mailer_item)
	{
		$this->doAdd($mailer_item);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_MailerItemResponseCollection extends app_mapper_Collection implements app_domain_MailerItemResponseCollection
{
	public function add(app_domain_MailerItemResponse $mailer_item_response)
	{
		$this->doAdd($mailer_item_response);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_MeetingCollection extends app_mapper_Collection implements app_domain_MeetingCollection
{
	public function add(app_domain_Meeting $meeting)
	{
		$this->doAdd($meeting);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_MessageCollection extends app_mapper_Collection implements app_domain_MessageCollection
{
	public function add(app_domain_Message $message)
	{
		$this->doAdd($message);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_PostCollection extends app_mapper_Collection implements app_domain_PostCollection
{
	public function add(app_domain_Post $post)
	{
		$this->doAdd($post);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_PostDisciplineReviewDateCollection extends app_mapper_Collection implements app_domain_PostDisciplineReviewDateCollection
{
	public function add(app_domain_PostDisciplineReviewDate $post_discipline_review_date)
	{
		$this->doAdd($post_discipline_review_date);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_PostAgencyUserCollection extends app_mapper_Collection implements app_domain_PostAgencyUserCollection
{
	public function add(app_domain_PostAgencyUser $post_agency_user)
	{
		$this->doAdd($post_agency_user);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_PostDecisionMakerCollection extends app_mapper_Collection implements app_domain_PostDecisionMakerCollection
{
	public function add(app_domain_PostDecisionMaker $post_decision_maker)
	{
		$this->doAdd($post_decision_maker);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_PostIncumbentAgencyCollection extends app_mapper_Collection implements app_domain_PostIncumbentAgencyCollection
{
	public function add(app_domain_PostIncumbentAgency $post_incumbent_agency)
	{
		$this->doAdd($post_incumbent_agency);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_PostInitiativeCollection extends app_mapper_Collection implements app_domain_PostInitiativeCollection
{
	public function add(app_domain_PostInitiative $post_initiative)
	{
		$this->doAdd($post_initiative);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_PostNoteCollection extends app_mapper_Collection implements app_domain_PostNoteCollection
{
	public function add(app_domain_PostNote $post_note)
	{
		$this->doAdd($post_note);
	}
}

/**
* @package Alchemis
*/
class app_mapper_PostInitiativeNoteDocumentCollection extends app_mapper_Collection implements app_domain_PostInitiativeNoteDocumentCollection
{
	public function add(app_domain_PostInitiativeNoteDocument $post_initiative_note_document)
	{
		$this->doAdd($post_initiative_note_document);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_ScoreboardCollection extends app_mapper_Collection implements app_domain_ScoreboardCollection
{
	public function add(app_domain_Scoreboard $scoreboard)
	{
		$this->doAdd($scoreboard);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_SiteCollection extends app_mapper_Collection implements app_domain_SiteCollection
{
	public function add(app_domain_Site $site)
	{
		$this->doAdd($site);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_TagCollection extends app_mapper_Collection implements app_domain_TagCollection
{
	public function add(app_domain_Tag $tag)
	{
		$this->doAdd($tag);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_TeamCollection extends app_mapper_Collection implements app_domain_TeamCollection
{
	public function add(app_domain_Team $team)
	{
		$this->doAdd($team);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_TeamNbmCollection extends app_mapper_Collection implements app_domain_TeamNbmCollection
{
	public function add(app_domain_TeamNbm $team_nbm)
	{
		$this->doAdd($team_nbm);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_TieredCharacteristicCollection extends app_mapper_Collection implements app_domain_TieredCharacteristicCollection
{
	public function add(app_domain_TieredCharacteristic $tiered_charcacteristic)
	{
		$this->doAdd($tiered_charcacteristic);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_ObjectTieredCharacteristicCollection extends app_mapper_Collection implements app_domain_ObjectTieredCharacteristicCollection
{
	public function add(app_domain_ObjectTieredCharacteristic $object_tiered_charcacteristic)
	{
		$this->doAdd($object_tiered_charcacteristic);
	}
}

/**
 * @package Alchemis
 */
class app_mapper_RegionCollection extends app_mapper_Collection implements app_domain_RegionCollection
{
	public function add(app_domain_Region $region)
	{
		$this->doAdd($region);
	}
}


/**
 * @package Alchemis
 */
class app_mapper_NbmCampaignTargetCollection extends app_mapper_Collection implements app_domain_CampaignNbmTargetCollection
{
	public function add(app_domain_CampaignNbmTarget $campaign_nbm_target)
	{
		$this->doAdd($campaign_nbm_target);
	}
}
?>