<?php

/**
 * Defines the app_domain_Finder interface and its subinterfaces.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Framework
 * @version   SVN: $Id$
 */

interface app_domain_Finder
{
	function find($id);
//	function findAll();
	function newId();
	function update(app_domain_DomainObject $object);
	function insert(app_domain_DomainObject $obj );
//	function delete();
}

//interface app_domain_SpaceFinder extends app_domain_Finder
//{
//	function findByVenue($id);
//}

interface app_domain_ActionFinder extends app_domain_Finder {}

interface app_domain_CampaignCompanyDoNotCallFinder extends app_domain_Finder {}

interface app_domain_CampaignFinder extends app_domain_Finder {}

interface app_domain_CampaignDisciplineFinder extends app_domain_Finder {}

interface app_domain_CampaignNbmFinder extends app_domain_Finder {}

interface app_domain_CampaignNbmTargetFinder extends app_domain_Finder {}

interface app_domain_CampaignRegionFinder extends app_domain_Finder {}

interface app_domain_CampaignReportSummaryFinder extends app_domain_Finder {}

interface app_domain_CampaignSectorFinder extends app_domain_Finder {}

interface app_domain_CampaignTargetFinder extends app_domain_Finder {}

interface app_domain_CharacteristicFinder extends app_domain_Finder {}

interface app_domain_CharacteristicElementFinder extends app_domain_Finder {}

interface app_domain_ClientFinder extends app_domain_Finder {}

interface app_domain_CmCampaignFinder extends app_domain_Finder {}

interface app_domain_CommunicationFinder extends app_domain_Finder {}

interface app_domain_CommunicationAttachmentFinder extends app_domain_Finder {}

interface app_domain_CompanyFinder extends app_domain_Finder {}

interface app_domain_CompanyNoteFinder extends app_domain_Finder {}

interface app_domain_ContactFinder extends app_domain_Finder {}

interface app_domain_DocumentFinder extends app_domain_Finder {}

interface app_domain_EventFinder extends app_domain_Finder {}

interface app_domain_FilterFinder extends app_domain_Finder {}

interface app_domain_FilterLineFinder extends app_domain_Finder {}

interface app_domain_ImportCompanyFinder extends app_domain_Finder {}

interface app_domain_ImportPostFinder extends app_domain_Finder {}

interface app_domain_InformationRequestFinder extends app_domain_Finder {}

interface app_domain_MailerFinder extends app_domain_Finder {}

interface app_domain_MailerItemFinder extends app_domain_Finder {}

interface app_domain_MailerItemResponseFinder extends app_domain_Finder {}

interface app_domain_MeetingFinder extends app_domain_Finder {}

interface app_domain_MessageFinder extends app_domain_Finder {}

interface app_domain_ObjectCharacteristicBooleanFinder extends app_domain_Finder {}

interface app_domain_ObjectCharacteristicDateFinder extends app_domain_Finder {}

interface app_domain_ObjectCharacteristicTextFinder extends app_domain_Finder {}

interface app_domain_ObjectCharacteristicElementBooleanFinder extends app_domain_Finder {}

interface app_domain_ObjectCharacteristicElementDateFinder extends app_domain_Finder {}

interface app_domain_ObjectCharacteristicElementTextFinder extends app_domain_Finder {}

interface app_domain_ObjectTieredCharacteristicFinder extends app_domain_Finder {}

interface app_domain_PostFinder extends app_domain_Finder {}

interface app_domain_PostDisciplineReviewDateFinder extends app_domain_Finder {}

interface app_domain_PostAgencyUserFinder extends app_domain_Finder {}

interface app_domain_PostDecisionMakerFinder extends app_domain_Finder {}

interface app_domain_PostIncumbentAgencyFinder extends app_domain_Finder {}

interface app_domain_PostInitiativeFinder extends app_domain_Finder {}

interface app_domain_PostInitiativeNoteFinder extends app_domain_Finder {}

interface app_domain_PostInitiativeNoteDocumentFinder extends app_domain_Finder {}

interface app_domain_PostNoteFinder extends app_domain_Finder {}

interface app_domain_ScoreboardFinder extends app_domain_Finder {}

interface app_domain_SearchFinder extends app_domain_Finder {}

interface app_domain_SiteFinder extends app_domain_Finder {}

interface app_domain_TagFinder extends app_domain_Finder {}

interface app_domain_TeamFinder extends app_domain_Finder {}

interface app_domain_TeamNbmFinder extends app_domain_Finder {}

interface app_domain_TieredCharacteristicFinder extends app_domain_Finder {}

interface app_domain_FilterBuilderFinder extends app_domain_Finder {}

interface app_domain_RegionFinder extends app_domain_Finder {}





interface app_domain_RbacCommandFinder extends app_domain_Finder {}
interface app_domain_RbacPermissionFinder extends app_domain_Finder {}
interface app_domain_RbacRoleFinder extends app_domain_Finder {}
interface app_domain_RbacUserFinder extends app_domain_Finder {}

?>