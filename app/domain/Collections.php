<?php

/**
 * Defines a series of Collection interfaces.
 * The Iterator interface is new in PHP 5. It requires that implementing 
 * classes define methods for querying a list. Doing this, the class can be 
 * used in foreach loops just like an array.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

interface app_domain_ActionCollection extends Iterator
{
	public function add(app_domain_Action $action);
}

interface app_domain_CampaignCollection extends Iterator
{
	public function add(app_domain_Campaign $campaign);
}

interface app_domain_CampaignDisciplineCollection extends Iterator
{
	public function add(app_domain_CampaignDiscipline $campaign_discipline);
}

interface app_domain_CampaignNbmCollection extends Iterator
{
	public function add(app_domain_CampaignNbm $campaign_nbm);
}

interface app_domain_CampaignNbmTargetCollection extends Iterator
{
	public function add(app_domain_CampaignNbmTarget $campaign_nbm_target);
}

interface app_domain_CampaignRegionCollection extends Iterator
{
	public function add(app_domain_CampaignRegion $campaign_region);
}

interface app_domain_CampaignReportSummaryCollection extends Iterator
{
	public function add(app_domain_CampaignReportSummary $campaign_report_summary);
}

interface app_domain_CampaignSectorCollection extends Iterator
{
	public function add(app_domain_CampaignSector $campaign_sector);
}

interface app_domain_CampaignTargetCollection extends Iterator
{
	public function add(app_domain_CampaignTarget $campaign_target);
}

interface app_domain_CampaignCompanyDoNotCallCollection extends Iterator
{
	public function add(app_domain_CampaignCompanyDoNotCall $campaign_company_do_not_call);
}

interface app_domain_CharacteristicCollection extends Iterator
{
	public function add(app_domain_Characteristic $characteristic);
}

interface app_domain_CharacteristicElementCollection extends Iterator
{
	public function add(app_domain_CharacteristicElement $element);
}

interface app_domain_ClientCollection extends Iterator
{
	public function add(app_domain_Client $client);
}

interface app_domain_CmCampaignCollection extends Iterator
{
	public function add(app_domain_CmCampaign $cmCampaign);
}

interface app_domain_CommunicationCollection extends Iterator
{
	public function add(app_domain_Communication $communication);
}

interface app_domain_CommunicationAttachmentCollection extends Iterator
{
	public function add(app_domain_CommunicationAttachment $communication_attachment);
}

interface app_domain_CompanyCollection extends Iterator
{
	public function add(app_domain_Company $company);
}

interface app_domain_CompanyNoteCollection extends Iterator
{
	public function add(app_domain_CompanyNote $company_note);
}

interface app_domain_DocumentCollection extends Iterator
{
	public function add(app_domain_Document $document);
}

interface app_domain_EventCollection extends Iterator
{
	public function add(app_domain_Event $event);
}

interface app_domain_TieredCharacteristicCollection extends Iterator
{
	public function add(app_domain_TieredCharacteristic $tiered_characteritic);
}

interface app_domain_ObjectTieredCharacteristicCollection extends Iterator
{
	public function add(app_domain_ObjectTieredCharacteristic $object_tiered_characteritic);
}

interface app_domain_PostCollection extends Iterator
{
	public function add(app_domain_Post $post);
}

interface app_domain_ContactCollection extends Iterator
{
	public function add(app_domain_Contact $contact);
}

interface app_domain_FilterCollection extends Iterator
{
	public function add(app_domain_Filter $filter);
}

interface app_domain_FilterLineCollection extends Iterator
{
	public function add(app_domain_FilterLine $filter_line);
}

interface app_domain_ImportCompanyCollection extends Iterator
{
    public function add(app_domain_ImportCompany $import);
}

interface app_domain_ImportPostCollection extends Iterator
{
    public function add(app_domain_ImportPost $import);
}

interface app_domain_MailerCollection extends Iterator
{
	public function add(app_domain_Mailer $mailer);
}

interface app_domain_MailerItemCollection extends Iterator
{
	public function add(app_domain_MailerItem $mailer_item);
}

interface app_domain_MailerItemResponseCollection extends Iterator
{
	public function add(app_domain_MailerItemResponse $mailer_item_response);
}

interface app_domain_MessageCollection extends Iterator
{
	public function add(app_domain_Message $message);
}

interface app_domain_PostAgencyUserCollection extends Iterator
{
	public function add(app_domain_PostAgencyUser $post_agency_user);
}

interface app_domain_PostDisciplineReviewDateCollection extends Iterator
{
	public function add(app_domain_PostDisciplineReviewDate $post_discipline_review_date);
}

interface app_domain_PostDecisionMakerCollection extends Iterator
{
	public function add(app_domain_PostDecisionMaker $post_decision_maker);
}

interface app_domain_PostIncumbentAgencyCollection extends Iterator
{
	public function add(app_domain_PostIncumbentAgency $post_incumbent_agency);
}

interface app_domain_PostInitiativeCollection extends Iterator
{
	public function add(app_domain_PostInitiative $post_initiative);
}

interface app_domain_PostInitiativeNoteCollection extends Iterator
{
	public function add(app_domain_PostInitiativeNote $post_initiative_note);
}

interface app_domain_PostInitiativeNoteDocumentCollection extends Iterator
{
	public function add(app_domain_PostInitiativeNoteDocument $post_initiative_note_document);
}

interface app_domain_SiteCollection extends Iterator
{
	public function add(app_domain_Site $site);
}

interface app_domain_MeetingCollection extends Iterator
{
	public function add(app_domain_Meeting $meeting);
}

interface app_domain_InformationRequestCollection extends Iterator
{
	public function add(app_domain_InformationRequest $information_request);
}

interface app_domain_PostNoteCollection extends Iterator
{
	public function add(app_domain_PostNote $post_note);
}

interface app_domain_ScoreboardCollection extends Iterator
{
	public function add(app_domain_Scoreboard $tag);
}

interface app_domain_TagCollection extends Iterator
{
	public function add(app_domain_Tag $tag);
}

interface app_domain_TeamCollection extends Iterator
{
	public function add(app_domain_Team $team);
}

interface app_domain_TeamNbmCollection extends Iterator
{
	public function add(app_domain_TeamNbm $team_nbm);
}

interface app_domain_FilterBuilderCollection extends Iterator
{
	public function add(app_domain_FilterBuilder $filter_builder);
}

interface app_domain_RegionCollection extends Iterator
{
	public function add(app_domain_Region $region);
}


?>