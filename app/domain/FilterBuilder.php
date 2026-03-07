<?php

/**
 * Defines the app_domain_FilterBuilder class.
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/DomainObject.php');
require_once('app/domain/Filter.php');
require_once('app/domain/Characteristic.php');
require_once('app/domain/CharacteristicElement.php');
require_once('app/domain/Communication.php');
require_once('include/Utils/Utils.class.php');

/**
 * @package Alchemis
 */
class app_domain_FilterBuilder extends app_domain_DomainObject
{


	public $fields;
//	protected $sql_query = array();
	protected $sql_exclude_query = array();
	protected $sql_include_query = array();
	protected $sql__exclude_query = array();

	/**
	 * @param integer $id
	 */
	public function __construct($id = null)
	{
		parent::__construct($id);

		$this->fields = self::getFieldSpec();

		if ($this->id)
		{
			// do nothing
		}
	}

	public static function getFieldSpecMap()
	{
		return [
			'parent company' => 'Company',
			'company' => 'Site',
			'post' => 'Post',
			'post initiative' => 'Post initiative',
			'meeting' => 'Meeting',
			'mailer' => 'Mailer'
		];
	}

	/**
	 * Returns an array of field validation rules.
	 * @see app_base_RuleValidator
	 */
	public static function getFieldSpec()
	{
		$fields = array ('parent company' => array(
			'name' => array(		'html_id'		=> 'name',
									'html_label'	=> 'Name',
									'html_type'		=> 'input',
									'html_style'	=> 'width: 200px',
									'input_type'	=> 'text',
									'data'			=> '',
									'ajax_command'	=> '',
									'operators'		=> array (	'starts with',
																'contains',
																'equals'),
									'domain'		=> 'Company',
									'sql_table'		=> 'tbl_parent_company',
									'sql_alias'		=> 'pc',
									'sql_join'		=> 'left join tbl_parent_company pc on c.parent_company_id = pc.id',
									'sql_field'		=> 'name',
									'sql_data_type'	=> 'string',
									'sql_source'	=> ''
									),
			'category' => array(	'html_id'		=> 'category',
									'html_label'	=> 'Category',
									'html_type'		=> 'select',
									'html_style'	=> 'width: 200px',
									'input_type'	=> '',
									'data'			=> '',
									'ajax_command'	=> '',
									'operators'		=> array (	'in'),
									'domain'		=> 'TieredCharacteristic',
									'sql_table'		=> 'tbl_object_tiered_characteristics',
									'sql_alias'		=> 'otc',
									'sql_join'		=> 'left join tbl_object_tiered_characteristics otc on c.parent_company_id = otc.parent_company_id', // OK to include tbl_companies in this join since it is always included in the query by default
									'sql_field'		=> 'tiered_characteristic_id',
									'sql_data_type'	=> 'number',
									'sql_source'	=> 'findRootTieredCharacteristicsArray',
									'sql_value_field' => 'id',
									'sql_text_field' => 'value'
									),
			'sub-category' => array(	'html_id'		=> 'sub_category',
									'html_label'	=> 'Sub-category',
									'html_type'		=> 'sub category',
									'html_style'	=> 'width: 200px',
									'input_type'	=> '',
									'data'			=> '',
									'ajax_command'	=> '',
									'operators'		=> array ('in'),
									'domain'		=> 'TieredCharacteristic',
									'sql_table'		=> 'tbl_company_tiered_characteristics',
									'sql_alias'		=> 'otc_sub',
									'sql_join'		=> 'left join tbl_object_tiered_characteristics otc_sub on c.parent_company_id = otc_sub.parent_company_id', // OK to include tbl_companies in this join since it is always included in the query by default
									'sql_field'		=> 'tiered_characteristic_id',
									'sql_data_type'	=> 'number',
									'sql_source'	=> '',
									'sql_value_field' => '',
									'sql_text_field' => ''
									),
			'tier' => array(	'html_id'		=> 'sub_category_tier',
									'html_label'	=> 'Sub-category',
									'html_type'		=> 'sub category tier',
									'html_style'	=> 'width: 100px',
									'input_type'	=> '',
									'data'			=> '',
									'ajax_command'	=> '',
									'operators'		=> array (	'equals',
																'greater than or equal to',
																'less than or equal to'),
									'domain'		=> 'TieredCharacteristic',
									'sql_table'		=> 'tbl_company_tiered_characteristics',
// 															'sql_alias'		=> 'otc_tier',
									'sql_alias'		=> 'otc_sub',
									'sql_join'		=> 'left join tbl_object_tiered_characteristics otc_sub on c.parent_company_id = otc_sub.parent_company_id', // OK to include tbl_companies in this join since it is always included in the query by default
//															'sql_join'		=> '',
									'sql_field'		=> 'tier',
									'sql_data_type'	=> 'number',
									'sql_source'	=> '',
									'sql_value_field' => '',
									'sql_text_field' => ''
									)
		),
		'company' => array(
									'name' => array(		'html_id'		=> 'name',
															'html_label'	=> 'Name',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'starts with',
																						'contains',
																						'equals'),
															'domain'		=> 'Company',
															'sql_table'		=> 'tbl_companies',
															'sql_alias'		=> 'c',
															'sql_join'		=> '', // not needed as tbl_companies vw_cs will always be included in the query by default
															'sql_field'		=> 'name',
															'sql_data_type'	=> 'string',
															'sql_source'	=> ''
															),
									'town' => array(		'html_id'		=> 'town',
															'html_label'	=> 'Town',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'starts with',
																						'contains',
																						'equals'),
															'domain'		=> 'Company',
															'sql_table'		=> 'tbl_sites',
															'sql_alias'		=> 's',
															'sql_join'		=> '', // not needed as tbl_companies vw_cs will always be included in the query by default
															'sql_field'		=> 'town',
															'sql_data_type'	=> 'string',
															'sql_source'	=> ''
															),
									'city' => array(		'html_id'		=> 'city',
															'html_label'	=> 'City',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'starts with',
																						'contains',
																						'equals'),
															'domain'		=> 'Company',
															'sql_table'		=> 'tbl_sites',
															'sql_alias'		=> 's',
															'sql_join'		=> '', // not needed as tbl_companies vw_cs will always be included in the query by default
															'sql_field'		=> 'city',
															'sql_data_type'	=> 'string',
															'sql_source'	=> ''
															),
									'postcode' => array(	'html_id'		=> 'postcode',
															'html_label'	=> 'Postcode',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'starts with',
																						'contains',
																						'equals'),
															'domain'		=> 'Company',
															'sql_table'		=> 'tbl_sites',
															'sql_alias'		=> 's',
															'sql_join'		=> '', // not needed as tbl_companies vw_cs will always be included in the query by default
															'sql_field'		=> 'postcode',
															'sql_data_type'	=> 'string',
															'sql_source'	=> ''
															),
									'telephone' => array(	'html_id'		=> 'telephone',
															'html_label'	=> 'Telephone',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'starts with',
																						'contains',
																						'equals'),
															'domain'		=> 'Company',
															'sql_table'		=> 'tbl_companies',
															'sql_alias'		=> 'c',
															'sql_join'		=> '', // not needed as tbl_companies c will always be included in the query by default
															'sql_field'		=> 'telephone',
															'sql_data_type'	=> 'string',
															'sql_source'	=> ''
															),
									'company telephone tps' => array(	'html_id'		=> 'company telephone tps',
															'html_label'	=> 'Company Telephone TPS',
															'html_type'		=> 'select_boolean',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array ('equals'),
															'domain'		=> 'Company',
															'sql_table'		=> 'tbl_companies',
															'sql_alias'		=> 'c',
															'sql_join'		=> '', // not needed as tbl_companies c will always be included in the query by default
															'sql_field'		=> 'telephone_tps',
															'sql_data_type'	=> 'boolean',
															'sql_source'	=> ''
															),
									'website' => array(		'html_id'		=> 'website',
															'html_label'	=> 'Website',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'starts with',
																						'contains',
																						'equals'),
															'domain'		=> 'Company',
															'sql_table'		=> 'tbl_companies',
															'sql_alias'		=> 'c',
															'sql_join'		=> '', // not needed as tbl_companies c will always be included in the query by default
															'sql_field'		=> 'website',
															'sql_data_type'	=> 'string',
															'sql_source'	=> ''
															),
									'county' => array(		'html_id'		=> 'county',
															'html_label'	=> 'County',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'in'),
															'domain'		=> 'Site',
															'sql_table'		=> 'tbl_sites',
															'sql_alias'		=> 's',
															'sql_join'		=> '', // not needed as tbl_sites s will always be included in the query by default
															'sql_field'		=> 'county_id',
															'sql_data_type'	=> 'string',
															'sql_source'	=> 'getCountiesAll',
															'sql_value_field' => 'id',
															'sql_text_field' => 'name'
															),
									'country' => array(		'html_id'		=> 'country',
															'html_label'	=> 'Country',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'in'),
															'domain'		=> 'Site',
															'sql_table'		=> 'tbl_sites',
															'sql_alias'		=> 's',
															'sql_join'		=> '', // not needed as tbl_sites s will always be included in the query by default
															'sql_field'		=> 'country_id',
															'sql_data_type'	=> 'string',
															'sql_source'	=> 'getCountriesAll',
															'sql_value_field' => 'id',
															'sql_text_field' => 'name'
															),
									'region' => array(		'html_id'		=> 'region',
															'html_label'	=> 'Region',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'in'),
															'domain'		=> 'Site',
															'sql_table'		=> 'tbl_lkp_regions',
 															'sql_alias'		=> 'lkp_r',
															'sql_join'		=> 'left join tbl_lkp_postcodes lkp_p on s.region_postcode = lkp_p.postcode left join tbl_lkp_region_postcodes lkp_rp on lkp_rp.postcode_id = lkp_p.id ' .
																				'left join tbl_lkp_regions lkp_r on lkp_rp.region_id = lkp_r.id', // OK to include s in this join since it is always included in the query by default
															'sql_field'		=> 'id',
															'sql_data_type'	=> 'number',
															'sql_source'	=> 'getRegionsAll',
															'sql_value_field' => 'id',
															'sql_text_field' => 'name'
															),
									'brand' => array(		'html_id'		=> 'brand',
															'html_label'	=> 'Brands',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'starts with',
																						'contains',
																						'equals'),
															'domain'		=> 'Tag',
															'sql_table'		=> 'tbl_company_tags',
 															'sql_alias'		=> 'tags1', // use ctags1 because 1 relates to the tag category id (brands). Further down the list we may need to use tbl_tags when querying for general tags (ctag2)
															'sql_join'		=> 'left join tbl_company_tags ctags1 on c.id = ctags1.company_id left join tbl_tags tags1 on tags1.id = ctags1.tag_id and tags1.category_id = 1', // OK to include tbl_companies in this join since it is always included in the query by default
															'sql_field'		=> 'value',
															'sql_data_type'	=> 'string',
															'sql_source'	=> ''
															),

									'category' => array(	'html_id'		=> 'category',
															'html_label'	=> 'Category',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'in'),
															'domain'		=> 'TieredCharacteristic',
															'sql_table'		=> 'tbl_object_tiered_characteristics',
 															'sql_alias'		=> 'otc',
															'sql_join'		=> 'left join tbl_object_tiered_characteristics otc on c.id = otc.company_id', // OK to include tbl_companies in this join since it is always included in the query by default
															'sql_field'		=> 'tiered_characteristic_id',
															'sql_data_type'	=> 'number',
															'sql_source'	=> 'findRootTieredCharacteristicsArray',
															'sql_value_field' => 'id',
															'sql_text_field' => 'value'
															),
									'sub-category' => array(	'html_id'		=> 'sub_category',
															'html_label'	=> 'Sub-category',
															'html_type'		=> 'sub category',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array ('in'),
															'domain'		=> 'TieredCharacteristic',
															'sql_table'		=> 'tbl_company_tiered_characteristics',
 															'sql_alias'		=> 'otc_sub',
															'sql_join'		=> 'left join tbl_object_tiered_characteristics otc_sub on c.id = otc_sub.company_id', // OK to include tbl_companies in this join since it is always included in the query by default
															'sql_field'		=> 'tiered_characteristic_id',
															'sql_data_type'	=> 'number',
															'sql_source'	=> '',
															'sql_value_field' => '',
															'sql_text_field' => ''
															),
									'sub-category tier' => array(	'html_id'		=> 'sub_category_tier',
															'html_label'	=> 'Sub-category',
															'html_type'		=> 'sub category tier',
															'html_style'	=> 'width: 100px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'equals',
																						'greater than or equal to',
																						'less than or equal to'),
															'domain'		=> 'TieredCharacteristic',
															'sql_table'		=> 'tbl_company_tiered_characteristics',
// 															'sql_alias'		=> 'otc_tier',
 															'sql_alias'		=> 'otc_sub',
															'sql_join'		=> 'left join tbl_object_tiered_characteristics otc_sub on c.id = otc_sub.company_id', // OK to include tbl_companies in this join since it is always included in the query by default
//															'sql_join'		=> '',
															'sql_field'		=> 'tier',
															'sql_data_type'	=> 'number',
															'sql_source'	=> '',
															'sql_value_field' => '',
															'sql_text_field' => ''
															),
									'tag' => array(			'html_id'		=> 'tags',
															'html_label'	=> 'Tags',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'starts with',
																						'contains',
																						'equals'),
															'domain'		=> 'Tag',
															'sql_table'		=> 'tbl_tags',
 															'sql_alias'		=> 'tags2', // use ctags2 because 2 relates to the tag category id. Further up the list we may already have use tbl_tags when querying for brands (ctag1)
															'sql_join'		=> 'left join tbl_company_tags ctags2 on c.id = ctags2.company_id left join tbl_tags tags2 on tags2.id = ctags2.tag_id and tags2.category_id = 2', // OK to include tbl_companies in this join since it is always included in the query by default
															'sql_field'		=> 'value',
															'sql_data_type'	=> 'string',
															'sql_source'	=> ''
															),
									'characteristic' =>
												array(		'html_id'		=> 'company_characteristic_id',
															'html_label'	=> 'Characteristic',
															'html_type'		=> 'company characteristic',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (),
															'domain'		=> '',
															'sql_table'		=> '',
															'sql_alias'		=> '',
															'sql_join'		=> '',
															'sql_field'		=> '',
															'sql_data_type'	=> '',
															'sql_source'	=> '',
															'sql_value_field' => '',
															'sql_text_field' => ''
															),
//									'do not call' => array(            'html_id'       => 'do_not_call',
//                                                            'html_label'    => 'Do not call',
//                                                            'html_type'     => 'input',
//                                                            'html_style'    => 'width: 200px',
//                                                            'input_type'    => '',
//                                                            'data'          => '',
//                                                            'ajax_command'  => '',
//                                                            'operators'     => array ('in'),
//                                                            'domain'        => 'CampaignCompanyDoNotCall',
//                                                            'sql_table'     => 'tbl_campaign_companies_do_not_call',
//                                                            'sql_alias'     => 'ccdnc', // use ctags2 because 2 relates to the tag category id. Further up the list we may already have use tbl_tags when querying for brands (ctag1)
//                                                            'sql_join'      => 'left join tbl_company_tags ctags2 on c.id = ctags2.company_id left join tbl_tags tags2 on tags2.id = ctags2.tag_id and tags2.category_id = 2', // OK to include tbl_companies in this join since it is always included in the query by default
//                                                            'sql_field'     => 'value',
//                                                            'sql_data_type' => 'string',
//                                                            'sql_source'    => ''
//                                                            ),
									),


						'post' => array(
									'job title' => array(	'html_id'		=> 'job_title',
															'html_label'	=> 'Job Title',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'starts with',
																						'contains',
																						'equals'),
															'domain'		=> 'Post',
															'sql_table'		=> 'tbl_posts',
															'sql_alias'		=> 'p',
															'sql_join'		=> '', // not needed as tbl_posts p will always be included in the query by default
															'sql_field'		=> 'job_title',
															'sql_data_type'	=> 'string',
															'sql_source'	=> ''
															),
									'telephone' => array(	'html_id'		=> 'telephone',
															'html_label'	=> 'Direct Line',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'starts with',
																						'contains',
																						'equals'),
															'domain'		=> 'Post',
															'sql_table'		=> 'tbl_posts',
															'sql_alias'		=> 'p',
															'sql_join'		=> '', // not needed as tbl_posts p will always be included in the query by default
															'sql_field'		=> 'telephone_1',
															'sql_data_type'	=> 'string',
															'sql_source'	=> ''
															),
									'email' => array(	'html_id'		=> 'email',
															'html_label'	=> 'Email',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'starts with',
																						'contains',
																						'equals'),
															'domain'		=> 'Contact',
															'sql_table'		=> 'tbl_contacts',
															'sql_alias'		=> 'con',
															'sql_join'		=> '', // not needed as tbl_posts p will always be included in the query by default
															'sql_field'		=> 'email',
															'sql_data_type'	=> 'string',
															'sql_source'	=> ''
															),
									'propensity' => array(	'html_id'		=> 'propensity',
															'html_label'	=> 'Propensity',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'equals',
																						'greater than or equal to',
																						'less than or equal to'),
															'domain'		=> 'Post',
															'sql_table'		=> 'tbl_posts',
															'sql_alias'		=> 'p',
															'sql_join'		=> '', // not needed as tbl_posts p will always be included in the query by default
															'sql_field'		=> 'propensity',
															'sql_data_type'	=> 'number',
															'sql_source'	=> ''
															),
									'in post' => array(		'html_id'		=> 'in post',
															'html_label'	=> 'in post',
															'html_type'		=> 'select_boolean',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array ('equals'),
															'domain'		=> '',
															'sql_table'		=> '',
															'sql_alias'		=> '',
															'sql_join'		=> '', // not needed as tbl_companies c will always be included in the query by default
															'sql_field'		=> '',
															'sql_data_type'	=> '',
															'sql_source'	=> ''
															),
									'decision maker' => array(	'html_id'		=> 'decision_maker',
															'html_label'	=> 'Decision maker',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'in'),
															'domain'		=> 'Communication',
															'sql_table'		=> 'tbl_post_decision_makers',
 															'sql_alias'		=> 'pdm',
															'sql_join'		=> 'left join tbl_post_decision_makers pdm on p.id = pdm.post_id', // OK to include tbl_posts in this join since it is always included in the query by default
															'sql_field'		=> 'type_id',
															'sql_data_type'	=> 'number',
															'sql_source'	=> 'lookupDecisonMakerOptions',
															'sql_value_field' => 'id',
															'sql_text_field' => 'description'
															),
									'decision maker discipline' =>
													array(	'html_id'		=> 'decision_maker_discipline',
															'html_label'	=> 'Decision maker discipline',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'in'),
															'domain'		=> 'Communication',
															'sql_table'		=> 'tbl_post_decision_makers',
 															'sql_alias'		=> 'pdm',
															'sql_join'		=> 'left join tbl_post_decision_makers pdm on p.id = pdm.post_id', // OK to include tbl_posts in this join since it is always included in the query by default
															'sql_field'		=> 'discipline_id',
															'sql_data_type'	=> 'number',
															'sql_source'	=> 'lookupDisciplineOptions',
															'sql_value_field' => 'id',
															'sql_text_field' => 'description'
															),
									'decision maker last updated' => array(
															'html_id'		=> 'decision_maker_last_updated',
															'html_label'	=> 'Decision maker last updated',
															'html_type'		=> 'date',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'since (and including)',
																						'since (and not including)',
																						'before (and including)',
																						'before (and not including)'),															'domain'		=> 'PostInitiative',
															'sql_table'		=> 'tbl_post_decision_makers',
															'sql_alias'		=> 'pdm',
															'sql_join'		=> 'left join tbl_post_decision_makers pdm on p.id = pdm.post_id', // OK to include tbl_posts in this join since it is always included in the query by default
															'sql_field'		=> 'last_updated_at',
															'sql_data_type'	=> 'datetime',
															'sql_source'	=> ''
															),
									'agency user' => array(	'html_id'		=> 'agency_user',
															'html_label'	=> 'Agency user',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'in'),
															'domain'		=> 'Communication',
															'sql_table'		=> 'tbl_post_agency_users',
 															'sql_alias'		=> 'pau',
															'sql_join'		=> 'left join tbl_post_agency_users pau on p.id = pau.post_id', // OK to include tbl_posts in this join since it is always included in the query by default
															'sql_field'		=> 'type_id',
															'sql_data_type'	=> 'number',
															'sql_source'	=> 'lookupAgencyUserOptions',
															'sql_value_field' => 'id',
															'sql_text_field' => 'description'
															),
									'agency user discipline' =>
													array(	'html_id'		=> 'agency_user_discipline',
															'html_label'	=> 'Agency user discipline',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'in'),
															'domain'		=> 'Communication',
															'sql_table'		=> 'tbl_post_agency_users',
 															'sql_alias'		=> 'pau',
															'sql_join'		=> 'left join tbl_post_agency_users pau on p.id = pau.post_id', // OK to include tbl_posts in this join since it is always included in the query by default
															'sql_field'		=> 'discipline_id',
															'sql_data_type'	=> 'number',
															'sql_source'	=> 'lookupDisciplineOptions',
															'sql_value_field' => 'id',
															'sql_text_field' => 'description'
															),
									'agency user last updated' => array(
															'html_id'		=> 'agency_user_last_updated',
															'html_label'	=> 'Agency user last updated',
															'html_type'		=> 'date',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'since (and including)',
																						'since (and not including)',
																						'before (and including)',
																						'before (and not including)'),															'domain'		=> 'PostInitiative',
															'sql_table'		=> 'tbl_post_agency_users',
															'sql_alias'		=> 'pau',
															'sql_join'		=> 'left join tbl_post_agency_users pau on p.id = pau.post_id', // OK to include tbl_posts in this join since it is always included in the query by default
															'sql_field'		=> 'last_updated_at',
															'sql_data_type'	=> 'datetime',
															'sql_source'	=> ''
															),
									'review date' =>
													array(	'html_id'		=> 'review_date',
															'html_label'	=> 'Review date',
															'html_type'		=> 'review date',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'equals',
																						'greater than or equal to',
																						'less than or equal to'),
															'domain'		=> '',
															'sql_table'		=> 'tbl_post_discipline_review_dates',
 															'sql_alias'		=> 'pdrd',
															'sql_join'		=> 'left join tbl_post_discipline_review_dates pdrd on p.id = pdrd.post_id', // OK to include tbl_posts in this join since it is always included in the query by default
															'sql_field'		=> '`year_month`',
															'sql_data_type'	=> 'string',
															'sql_source'	=> '',
															'sql_value_field' => '',
															'sql_text_field' => ''
															),
									'review date discipline' =>
													array(	'html_id'		=> 'review_date_discipline',
															'html_label'	=> 'Review date discipline',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'in'),
															'domain'		=> 'Communication',
															'sql_table'		=> 'tbl_post_discipline_review_dates',
 															'sql_alias'		=> 'pdrd',
															'sql_join'		=> 'left join tbl_post_discipline_review_dates pdrd on p.id = pdrd.post_id', // OK to include tbl_posts in this join since it is always included in the query by default
															'sql_field'		=> 'discipline_id',
															'sql_data_type'	=> 'number',
															'sql_source'	=> 'lookupDisciplineOptions',
															'sql_value_field' => 'id',
															'sql_text_field' => 'description'
															),
									'review date last updated' => array(
															'html_id'		=> 'review_date_last_updated',
															'html_label'	=> 'Review date last updated',
															'html_type'		=> 'date',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'since (and including)',
																						'since (and not including)',
																						'before (and including)',
																						'before (and not including)'),															'domain'		=> 'PostInitiative',
															'sql_table'		=> 'tbl_post_discipline_review_dates',
															'sql_alias'		=> 'pdrd',
															'sql_join'		=> 'left join tbl_post_discipline_review_dates pdrd on p.id = pdrd.post_id', // OK to include tbl_posts in this join since it is always included in the query by default
															'sql_field'		=> 'last_updated_at',
															'sql_data_type'	=> 'datetime',
															'sql_source'	=> ''
															),
									'tag' => array(			'html_id'		=> 'tags',
															'html_label'	=> 'Tags',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'starts with',
																						'contains',
																						'equals'),
															'domain'		=> 'Tag',
															'sql_table'		=> 'tbl_tags',
 															'sql_alias'		=> 'tags2p', // use tags2p because 2 relates to the tag category id (general) and 'p' to posts (basically we're creating a unique table alias). Further up the list we may already have use tbl_tags when querying for brands (tag1)
															'sql_join'		=> 'left join tbl_post_tags ptags2 on p.id = ptags2.post_id left join tbl_tags tags2p on tags2p.id = ptags2.tag_id and tags2p.category_id = 2', // OK to include tbl_companies in this join since it is always included in the query by default
															'sql_field'		=> 'value',
															'sql_data_type'	=> 'string',
															'sql_source'	=> ''
															),
									'characteristic' =>
												array(		'html_id'		=> 'post_characteristic_id',
															'html_label'	=> 'Characteristic',
															'html_type'		=> 'post characteristic',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (),
															'domain'		=> '',
															'sql_table'		=> '',
															'sql_alias'		=> '',
															'sql_join'		=> '',
															'sql_field'		=> '',
															'sql_data_type'	=> '',
															'sql_source'	=> '',
															'sql_value_field' => '',
															'sql_text_field' => ''
															),

//									'brands' => array(		'html_id'		=> 'agency_user',
//															'html_label'	=> 'Agency User',
//															'html_type'		=> 'screen',
//															'html_style'	=> 'width: 200px',
//															'input_type'	=> 'text',
//															'data'			=> '',
//															'ajax_command'	=> '',
//															'operators'		=> array (	'starts with',
//																						'contains',
//																						'equals'),
//															'domain'		=> 'Post',
//															'sql_table'		=> 'tbl_posts',
//															'sql_field'		=> 'postcode',
//															'sql_data_type'	=> 'string',
//															'sql_source'	=> ''
//															)
									),

						'post initiative' => array(

									'status' => array(		'html_id'		=> 'status',
															'html_label'	=> 'Status',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'in'),
															'domain'		=> 'Communication',
															'sql_table'		=> 'tbl_post_initiatives',
															'sql_alias'		=> 'pi',
															'sql_join'		=> '', // not needed as tbl_post_initiatives will always be included in the query by default
															'sql_field'		=> 'status_id',
															'sql_data_type'	=> 'number',
															'sql_source'	=> 'findStatusAll',
															'sql_value_field' => 'id',
															'sql_text_field' => 'description'
															),
									'last communication' => array(	'html_id'		=> 'last_communication',
															'html_label'	=> 'Last Communication',
															'html_type'		=> 'date',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'date',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'since (and including)',
																						'since (and not including)',
																						'before (and including)',
																						'before (and not including)'),
															'domain'		=> 'PostInitiative',
															'sql_table'		=> 'tbl_communications com_last',
															'sql_alias'		=> 'com_last',
															'sql_join'		=> 'left join tbl_communications com_last on pi.last_communication_id = com_last.id',
															'sql_field'		=> 'communication_date',
															'sql_data_type'	=> 'datetime',
															'sql_source'	=> ''
															),
									'last effective' => array(	'html_id'		=> 'last_effective',
															'html_label'	=> 'Last Effective',
															'html_type'		=> 'date',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'since (and including)',
																						'since (and not including)',
																						'before (and including)',
																						'before (and not including)'),
															'domain'		=> 'PostInitiative',
															'sql_table'		=> 'tbl_communications com_eff',
															'sql_alias'		=> 'com_eff',
															'sql_join'		=> 'left join tbl_communications com_eff on pi.last_effective_communication_id = com_eff.id',
															'sql_field'		=> 'communication_date',
															'sql_data_type'	=> 'datetime',
															'sql_source'	=> ''
															),
                                    'last effective by' => array(   'html_id'       => 'last_effective by',
                                                            'html_label'    => 'Status',
                                                            'html_type'     => 'select',
                                                            'html_style'    => 'width: 200px',
                                                            'input_type'    => '',
                                                            'data'          => '',
                                                            'ajax_command'  => '',
                                                            'operators'     => array (  'in'),
                                                            'domain'        => 'RbacUser',
                                                            'sql_table'     => 'tbl_communications com_eff_by_nbm',
                                                            'sql_alias'     => 'com_eff_by_nbm',
                                                            'sql_join'      => 'left join tbl_communications com_eff_by_nbm on pi.last_effective_communication_id = com_eff_by_nbm.id',
                                                            'sql_field'     => 'user_id',
                                                            'sql_data_type' => 'number',
                                                            'sql_source'    => '',
                                                            'sql_value_field' => 'id',
                                                            'sql_text_field' => 'name'
                                                            ),
									'next communication' => array(
															'html_id'		=> 'next_communication',
															'html_label'	=> 'Next Communication',
															'html_type'		=> 'date',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'since (and including)',
																						'since (and not including)',
																						'before (and including)',
																						'before (and not including)'),
															'domain'		=> 'PostInitiative',
															'sql_table'		=> 'tbl_post_initiatives',
															'sql_alias'		=> 'pi',
															'sql_join'		=> '', // not needed as tbl_post_initiatives pi will always be included in the query by default
															'sql_field'		=> 'next_communication_date',
															'sql_data_type'	=> 'datetime',
															'sql_source'	=> ''
															),
									'communication type' => array(
															'html_id'		=> 'communication_type',
															'html_label'	=> 'Communication type',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'in'),
															'domain'		=> 'Communication',
															'sql_table'		=> 'tbl_communications com_type',
															'sql_alias'		=> 'com_type',
															'sql_join'		=> 'left join tbl_communications com_type on pi.last_communication_id = com_type.id',
															'sql_field'		=> 'type_id',
															'sql_data_type'	=> 'number',
															'sql_source'	=> 'findTypesAllActive',
															'sql_value_field' => 'id',
															'sql_text_field' => 'description'
															),
									'initiative' => array(		'html_id'		=> 'initiative_id',
															'html_label'	=> 'Initiative',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'in'),
															'domain'		=> 'CampaignNbm',
															'sql_table'		=> 'tbl_post_initiatives',
															'sql_alias'		=> 'pi',
															'sql_join'		=> '', // not needed as tbl_post_initiatives pi will always be included in the query by default
															'sql_field'		=> 'initiative_id',
															'sql_data_type'	=> 'number',
															'sql_source'	=> 'findCampaignInitiativesByCurrentUser',
															'sql_value_field' => 'initiative_id',
															'sql_text_field' => 'client_initiative_display'
															),
									'project ref' => array(	'html_id'		=> 'tag_project_ref',
															'html_label'	=> 'Project Ref',
															'html_type'		=> 'input',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'text',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'starts with',
																						'contains',
																						'equals'),
															'domain'		=> 'Tag',
															'sql_table'		=> 'tbl_tags',
															'sql_alias'		=> 't',
															'sql_join'		=> 'left join tbl_post_initiative_tags pit ON pit.post_initiative_id = pi.id left join tbl_tags t on pit.tag_id = t.id and t.category_id = 3 ',
															'sql_field'		=> 'value',
															'sql_data_type'	=> 'string',
															'sql_source'	=> '',
															'sql_value_field' => '',
															'sql_text_field' => ''
															),
								    'lead source' =>
                                                array(      'html_id'       => 'lead_source',
                                                            'html_label'    => 'Lead Source',
                                                            'html_type'     => 'select',
                                                            'html_style'    => 'width: 200px',
                                                            'input_type'    => '',
                                                            'data'          => '',
                                                            'ajax_command'  => '',
                                                            'operators'     => array (  'in'),
                                                            'domain'        => 'PostInitiative',
                                                            'sql_table'     => 'tbl_post_initiatives',
                                                            'sql_alias'     => 'pi',
                                                            'sql_join'      => '', // not needed as tbl_post_initiatives pi will always be included in the query by default
                                                            'sql_field'     => 'lead_source_id',
                                                            'sql_data_type' => 'number',
                                                            'sql_source'    => 'lookupLeadSourceAll',
                                                            'sql_value_field' => 'id',
                                                            'sql_text_field' => 'description'
                                                            ),
									'information request' =>
													array(	'html_id'		=> 'information_request',
															'html_label'	=> 'Information Request',
															'html_type'		=> 'date',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'since (and including)',
																						'since (and not including)',
																						'before (and including)',
																						'before (and not including)'),
															'domain'		=> 'InformationRequest',
															'sql_table'		=> 'tbl_actions',
															'sql_alias'		=> 'a',
//															'sql_join'		=> 'left join tbl_information_requests ir ON ir.post_initiative_id = pi.id ',
															'sql_join'		=> 'left join tbl_actions a ON a.post_initiative_id = pi.id and a.type_id = 2 ',
															'sql_field'		=> 'created_at',
															'sql_data_type'	=> 'datetime',
															'sql_source'	=> ''
															),
									'characteristic' =>
												array(		'html_id'		=> 'post_initiative_characteristic_id',
															'html_label'	=> 'Characteristic',
															'html_type'		=> 'post initiative characteristic',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (),
															'domain'		=> '',
															'sql_table'		=> '',
															'sql_alias'		=> '',
															'sql_join'		=> '',
															'sql_field'		=> '',
															'sql_data_type'	=> '',
															'sql_source'	=> '',
															'sql_value_field' => '',
															'sql_text_field' => ''
															)
									),

						'meeting' => array (
									                        'meeting set date' => array( 'html_id'       => 'meeting_set_date',
                                                            'html_label'    => 'Meeting Set Date',
                                                            'html_type'     => 'date',
                                                            'html_style'    => 'width: 200px',
                                                            'input_type'    => 'date',
                                                            'data'          => '',
                                                            'ajax_command'  => '',
                                                            'operators'     => array (  'since (and including)',
                                                                                        'since (and not including)',
                                                                                        'before (and including)',
                                                                                        'before (and not including)'),
                                                            'domain'        => 'Meeting',
                                                            'sql_table'     => 'tbl_meetings',
                                                            'sql_alias'     => 'm',
                                                            'sql_join'      => '',
                                                            'sql_field'     => 'created_at',
                                                            'sql_data_type' => 'datetime',
                                                            'sql_source'    => ''
                                                            ),
                                    'meeting date' => array( 'html_id'       => 'meeting_date',
                                                            'html_label'    => 'Meeting Date',
                                                            'html_type'     => 'date',
                                                            'html_style'    => 'width: 200px',
                                                            'input_type'    => 'date',
                                                            'data'          => '',
                                                            'ajax_command'  => '',
                                                            'operators'     => array (  'since (and including)',
                                                                                        'since (and not including)',
                                                                                        'before (and including)',
                                                                                        'before (and not including)'),
                                                            'domain'        => 'Meeting',
                                                            'sql_table'     => 'tbl_meetings',
                                                            'sql_alias'     => 'm',
                                                            'sql_join'      => '',
//                                                            'sql_join'      => 'left join tbl_meetings m on pi.id = m.post_initiative_id ',
                                                            'sql_field'     => 'date',
                                                            'sql_data_type' => 'datetime',
                                                            'sql_source'    => ''
                                                            ),
                                     'meeting attended date' =>
                                                array(      'html_id'       => 'meeting_attended_date',
                                                            'html_label'    => 'Meeting Attended Date',
                                                            'html_type'     => 'date',
                                                            'html_style'    => 'width: 200px',
                                                            'input_type'    => 'date',
                                                            'data'          => '',
                                                            'ajax_command'  => '',
                                                            'operators'     => array (  'since (and including)',
                                                                                        'since (and not including)',
                                                                                        'before (and including)',
                                                                                        'before (and not including)'),
                                                            'domain'        => 'Meeting',
                                                            'sql_table'     => 'tbl_meetings',
                                                            'sql_alias'     => 'm',
                                                            'sql_join'      => '',
                                                            'sql_field'     => 'attended_date',
                                                            'sql_data_type' => 'datetime',
                                                            'sql_source'    => ''
                                                            ),
                                    'meeting set by' =>
                                                 array(     'html_id'       => 'meeting_set_by',
                                                            'html_label'    => 'Meeting Set By',
                                                            'html_type'     => 'select',
                                                            'html_style'    => 'width: 200px',
                                                            'input_type'    => '',
                                                            'data'          => '',
                                                            'ajax_command'  => '',
                                                            'operators'     => array (  'in'),
                                                            'domain'        => 'RbacUser',
                                                            'sql_table'     => 'tbl_meetings',
                                                            'sql_alias'     => 'm',
                                                            'sql_join'      => '',
                                                            'sql_field'     => 'created_by',
                                                            'sql_data_type' => 'number',
                                                            'sql_source'    => 'findAllActiveForFilterDropdown',
                                                            'sql_value_field' => 'id',
                                                            'sql_text_field' => 'name'
                                                            ),
                                    'meeting status' =>
                                                array(      'html_id'       => 'meeting_status',
                                                            'html_label'    => 'Meeting Status',
                                                            'html_type'     => 'select',
                                                            'html_style'    => 'width: 200px',
                                                            'input_type'    => '',
                                                            'data'          => '',
                                                            'ajax_command'  => '',
                                                            'operators'     => array (  'in'),
                                                            'domain'        => 'Meeting',
                                                            'sql_table'     => 'tbl_meetings',
                                                            'sql_alias'     => 'm',
                                                            'sql_join'      => '',
                                                            'sql_field'     => 'status_id',
                                                            'sql_data_type' => 'number',
                                                            'sql_source'    => 'lookupStatuses',
                                                            'sql_value_field' => 'id',
                                                            'sql_text_field' => 'description'
                                                            )
									),
						'mailer' => array(

//									'name' => array(		'html_id'		=> 'name',
//															'html_label'	=> 'Name',
//															'html_type'		=> 'select',
//															'html_style'	=> 'width: 400px',
//															'input_type'	=> '',
//															'data'			=> '',
//															'ajax_command'	=> '',
//															'operators'		=> array (	'in'),
//															'domain'		=> 'Mailer',
//															'sql_table'		=> 'tbl_mailers',
//															'sql_alias'		=> 'm',
//															'sql_join'		=> 'left join tbl_mailer_items mi on mi.post_initiative_id = pi.id left join tbl_mailers m on m.id = mi.mailer_id ',
//															'sql_field'		=> 'id',
//															'sql_data_type'	=> 'number',
//															'sql_source'	=> 'findAllMailerIdsAndNames',
//															'sql_value_field' => 'id',
//															'sql_text_field' => 'name'
//															),
									'name' => array(		'html_id'		=> 'name',
															'html_label'	=> 'Name',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 400px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'in'),
															'domain'		=> 'Mailer',
															'sql_table'		=> 'tbl_mailer_items',
															'sql_alias'		=> 'mi',
															'sql_join'		=> 'left join tbl_mailer_items mi on mi.post_initiative_id = pi.id ',
															'sql_field'		=> 'mailer_id',
															'sql_data_type'	=> 'number',
															'sql_source'	=> 'findAllMailerIdsAndNames',
															'sql_value_field' => 'id',
															'sql_text_field' => 'name'
															),
									'despatched date' => array(
															'html_id'		=> 'despatched date',
															'html_label'	=> 'Despatched Date',
															'html_type'		=> 'date',
															'html_style'	=> 'width: 200px',
															'input_type'	=> 'date',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'since (and including)',
																						'since (and not including)',
																						'before (and including)',
																						'before (and not including)'),
															'domain'		=> 'MailerItem',
															'sql_table'		=> 'tbl_mailer_items',
															'sql_alias'		=> 'mi',
															'sql_join'		=> 'left join tbl_mailer_items mi on mi.post_initiative_id = pi.id ',
															'sql_field'		=> 'despatched_date',
															'sql_data_type'	=> 'datetime',
															'sql_source'	=> ''
															),
									'response date' => array(
															'html_id'		=> 'response date',
															'html_label'	=> 'Response Date',
															'html_type'		=> 'date',
															'html_style'	=> 'width: 200px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array (	'since (and including)',
																						'since (and not including)',
																						'before (and including)',
																						'before (and not including)'),
															'domain'		=> 'PostInitiative',
															'sql_table'		=> 'tbl_mailer_items',
															'sql_alias'		=> 'mi',
															'sql_join'		=> 'left join tbl_mailer_items mi on mi.post_initiative_id = pi.id ',
															'sql_field'		=> 'response_date',
															'sql_data_type'	=> 'datetime',
															'sql_source'	=> ''
															),
									'response' => array(	'html_id'		=> 'response',
															'html_label'	=> 'Response',
															'html_type'		=> 'select',
															'html_style'	=> 'width: 400px',
															'input_type'	=> '',
															'data'			=> '',
															'ajax_command'	=> '',
															'operators'		=> array ('in'),
															'domain'		=> 'MailerItemResponse',
															'sql_table'		=> 'tbl_mailer_item_responses',
															'sql_alias'		=> 'mir',
															'sql_join'		=> array(	'left join tbl_mailer_items mi on mi.post_initiative_id = pi.id ',
																					 	'left join tbl_mailer_item_responses mir on mir.mailer_item_id = mi.id '),
															'sql_field'		=> 'mailer_response_id',
															'sql_data_type'	=> 'number',
															'sql_source'	=> 'findAllPossibleResponses',
															'sql_value_field' => 'id',
															'sql_text_field' => 'description'
															),
									)
//						'mailer' => array(
//									''
//						                                    'html_id'       => 'nbm',
//                                                            'html_label'    => 'Status',
//                                                            'html_type'     => 'select',
//                                                            'html_style'    => 'width: 200px',
//                                                            'input_type'    => '',
//                                                            'data'          => '',
//                                                            'ajax_command'  => '',
//                                                            'operators'     => array (  'in'),
//                                                            'domain'        => 'RbacUser',
//                                                            'sql_table'     => 'tbl_communications com_by_nbm',
//                                                            'sql_alias'     => 'com_by_nbm',
//                                                            'sql_join'      => 'left join tbl_communications com_by_nbm on pi.id = com_by_nbm.post_initiative_id',
//                                                            'sql_field'     => 'user_id',
//                                                            'sql_data_type' => 'number',
//                                                            'sql_source'    => '',
//                                                            'sql_value_field' => 'id',
//                                                            'sql_text_field' => 'name'
//                                                            );
						);

					// Get user information from the session
                    $session = Auth_Session::singleton();
                    $user = $session->getSessionUser();
                    $user = app_domain_RbacUser::find($user['id']);

                    if (!empty($user) && $user->hasPermission('permission_admin_reports')) {
                    	$fields['post initiative']['last effective by']['sql_source'] = 'findAllActiveForFilterDropdown';
											$fields['post initiative']['last effective by']['sql_source_params'] = [$user->getClientId()];
					} else {
                        $fields['post initiative']['last effective by']['sql_source'] = 'findCurrentUserForDropdown';
					}


		return $fields;
	}

	public static function getDataGroupsByParentGroup($level = null)
	{

		$result = array();
		$array = self::getFieldSpec();
//		print_r($array);

		if (!is_null($level))
		{
			$array = $array[$level];
		}


		foreach ($array as $key => $item)
		{

//			echo $key . '<br />';
			$result[$key] = $key;
		}

		return $result;
	}

	/**
	* Saves the filter lines parameters for a filter object
	* @param string $line_items - items selected by the user, either for the include or exclude statements
	* @param string $filter_id - id of the filter
	* @param string $direction - specifies whether we are making an include or exclude statement
	*/
	public function saveLineItems($line_items, $filter_id, $direction)
	{

		try
		{
			app_domain_Filter::deleteFilterLinesByIdAndDirection($filter_id, $direction);

			foreach ($line_items as $item)
			{

				$line_item = new app_domain_FilterLine();

				$line_item->setFilterId($filter_id);
				$line_item->setTableName($item->where_table);
				$line_item->setFieldName($item->where_field);
				$line_item->setParams($item->where_data);
				$line_item->setParamsDisplay($item->where_data_display);
				$line_item->setOperator($item->where_operator);
				$line_item->setConcatenator($item->concatenator);
				$line_item->setBracketOpen($item->bracket_open);
				$line_item->setBracketClose($item->bracket_close);
				$line_item->setDirection($direction);
				$line_item->commit();
			}
		}
		catch(Exception $e)
		{
			throw new Exception('Error processing filter lines');
		}
	}

	/**
	* Builds the sql where clauses for the filter
	* @param string $line_items - items selected by the user, either for the include or exclude statements
	* @param string $direction - specifies whether we are making an include or exclude statement
	*/
	public function makeSQLData($id, $line_items, $direction)
	{

		$where = '';
		$main = '';
		$join_clause = '';
        $campaign_companies_do_not_call_count = 0;

		$filter = app_domain_filter::find($id);

		$results_format = $filter->getResultsFormat();

//		print_r($line_items);

//		 echo '<pre>';
//          print_r($line_items);
//          echo '</pre>';

		$spec_array = self::getFieldSpec();

//		            echo '<pre>';
//          print_r($spec_array);
//          echo '</pre>';



		// Set up an array to record which join statements have already been used - only need to make a join once for each table
		$join_array = array();

		// Create a variable to hold any additional where statements we may want to create within the loop in addition to the 'standard'
		// where clause
		$where_additional = '';

		// create a variable to hold the line count - we can use this to make all the table join aliases unique
		$line_count = 0;

		foreach ($line_items as $item)
		{
			$line_count++;
//			echo '<pre>';
//			print_r($item);
//			echo '</pre>';

			$params = $item['params'];

			// convert any strings to arrays in order to make the criteria to be used in the
			// sql string that is executed to create the list
			if (!is_array($params))
			{
				$params = explode(",",$params);
			}

			// if $item['field_name'] has a . in it then it means its a composite - ie its not just a standard default
			// database field, but is user-defined - eg its either a characteristic, tiered characteristic, or a tag
			if (substr_count($item['field_name'], '.') > 0)
			{
				$field_names = explode(".",$item['field_name']);

				switch (strtolower($field_names[0]))
				{
					case 'characteristic':
						$characteristic_name = $field_names[1];
						//get the characteristic
						if ($characteristic = app_domain_Characteristic::findByNameAndType($characteristic_name, $item['table_name']))
						{
							if ($characteristic->hasAttributes())
							{
								// Check at this point that we do have an element name - should be in $field_names[2].
								// If not then we can't proceed
								if (!isset($field_names[2]))
								{
									throw new Exception('characteristic ' . $characteristic_name . ' of type ' . $item['table_name'] . ' expected an element name which was not supplied');
								}

								// Get the relevant characteristic element
								$element_name = $field_names[2];
								if ($element = app_domain_CharacteristicElement::findByCharacteristicIdAndName($characteristic->getId(), $element_name))
								{
									// At this point we always need to join tbl_object_characteristics (if not already done so) as it forms the link
									// between elements and characteristics. May need to link up to three times for each type of data level (eg company, post)
									switch($item['table_name'])
									{
										case 'company':
											$table_alias = 'obj_char_company_' . $line_count;
											if (!in_array('obj_char_company_' . $line_count, $join_array))
											{
												// NOTE: need space at end of join statement
												$join = 'left join tbl_object_characteristics obj_char_company_' . $line_count . ' on c.id = obj_char_company_' . $line_count . '.company_id ';
												$join_array[] = 'obj_char_company_' . $line_count;
											}
											else
											{
												$join = '';
											}
											break;
										case 'post':
											$table_alias = 'obj_char_post_' . $line_count;
											if (!in_array('obj_char_post_' . $line_count, $join_array))
											{
												// NOTE: need space at end of join statement
												$join = 'left join tbl_object_characteristics obj_char_post_' . $line_count . ' on p.id = obj_char_post_' . $line_count . '.post_id ';
												$join_array[] = 'obj_char_txt_post_' . $line_count;
											}
											else
											{
												$join = '';
											}
											break;
										case 'post initiative':
											$table_alias = 'obj_char_post_initiative_' . $line_count;
											if (!in_array('obj_char_post_initiative_' . $line_count, $join_array))
											{
												// NOTE: need space at end of join statement
												$join = 'left join tbl_object_characteristics obj_char_post_initiative_' . $line_count . ' on pi.id = obj_char_post_initiative_' . $line_count . '.post_initiative_id ';
												$join_array[] = 'obj_char_post_initiative_' . $line_count;
											}
											else
											{
												$join = '';
											}
											break;
										default:
											$join = '';
											break;
									}

									// Get the data_type of the characteristic and lookup which tables we need to join
									// NOTE: the $data_type variable set below relate to the data types used in the makeCriteria function
									switch ($element->getDataType())
									{
										case 'text':
											switch($item['table_name'])
											{
												case 'company':
													$table_alias = 'obj_char_elem_txt_company_' . $line_count;
													if (!in_array('obj_char_elem_txt_company_' . $line_count, $join_array))
													{
														// if $join is != "" then need to add to the join string rather than overwriting it
														$join .= 'left join tbl_object_characteristic_elements_text obj_char_elem_txt_company_' . $line_count . ' on obj_char_company_' . $line_count . '.id = obj_char_elem_txt_company_' . $line_count . '.object_characteristic_id';
														$join_array[] = 'obj_char_elem_txt_company_' . $line_count;
													}
													else
													{
														$join = '';
													}
													$where_additional = ' and obj_char_elem_txt_company_' . $line_count . '.characteristic_element_id = ' . $element->getId();
													break;
												case 'post':
													$table_alias = 'obj_char_elem_txt_post_' . $line_count;
													if (!in_array('obj_char_elem_txt_post_' . $line_count, $join_array))
													{
														// if $join is != "" then need to add to the join string rather than overwriting it
														$join .= 'left join tbl_object_characteristic_elements_text obj_char_elem_txt_post_' . $line_count . ' on obj_char_post_' . $line_count . '.id = obj_char_elem_txt_post_' . $line_count . '.object_characteristic_id';
														$join_array[] = 'obj_char_elem_txt_post_' . $line_count;
													}
													else
													{
														$join = '';
													}
													$where_additional = ' and obj_char_elem_txt_post_' . $line_count . '.characteristic_element_id = ' . $element->getId();
													break;
												case 'post initiative':
													$table_alias = 'obj_char_elem_txt_post_initiative_' . $line_count;
													if (!in_array('obj_char_elem_txt_post_initiative_' . $line_count, $join_array))
													{
														// if $join is != "" then need to add to the join string rather than overwriting it
														$join .= 'left join tbl_object_characteristic_elements_text obj_char_elem_txt_post_initiative_' . $line_count . ' on obj_char_post_initiative_' . $line_count . '.id = obj_char_elem_txt_post_initiative_' . $line_count . '.object_characteristic_id';
														$join_array[] = 'obj_char_elem_txt_post_initiative_' . $line_count;
													}
													else
													{
														$join = '';
													}
													$where_additional = ' and obj_char_elem_txt_post_initiative_' . $line_count . '.characteristic_element_id = ' . $element->getId();
													break;
												default:
													$join = '';
													break;
											}

											$field_name = 'value';
											$data_type = 'string';
											break;
										case 'boolean':
											switch($item['table_name'])
											{
												case 'company':
													$table_alias = 'obj_char_elem_bln_company_' . $line_count;
													if (!in_array('obj_char_elem_bln_company_' . $line_count,$join_array))
													{
														$join .= 'left join tbl_object_characteristic_elements_boolean obj_char_elem_bln_company_' . $line_count . ' on obj_char_company_' . $line_count . '.id = obj_char_elem_bln_company_' . $line_count . '.object_characteristic_id';
														$join_array[] = 'obj_char_elem_bln_company_' . $line_count;
													}
													else
													{
														$join = '';
													}
													$where_additional = ' and obj_char_elem_bln_company_' . $line_count . '.characteristic_element_id = ' . $element->getId();
													break;
												case 'post':
													$table_alias = 'obj_char_elem_bln_post_' . $line_count;
													if (!in_array('obj_char_elem_bln_post_' . $line_count, $join_array))
													{
														$join .= 'left join tbl_object_characteristic_elements_boolean obj_char_elem_bln_post_' . $line_count . ' on obj_char_post_' . $line_count . '.id = obj_char_elem_bln_post_' . $line_count . '.object_characteristic_id';
														$join_array[] = 'obj_char_elem_bln_post_' . $line_count;
													}
													else
													{
														$join = '';
													}
													$where_additional = ' and obj_char_elem_bln_post_' . $line_count . '.characteristic_element_id = ' . $element->getId();
													break;
												case 'post initiative':
													$table_alias = 'obj_char_elem_bln_post_initiative_' . $line_count;
													if (!in_array('obj_char_elem_bln_post_initiative_' . $line_count,$join_array))
													{
														$join .= 'left join tbl_object_characteristic_elements_boolean obj_char_elem_bln_post_initiative_' . $line_count . ' on obj_char_post_initiative_' . $line_count . '.id = obj_char_elem_bln_post_initiative_' . $line_count . '.object_characteristic_id';
														$join_array[] = 'obj_char_elem_bln_post_initiative_' . $line_count;
													}
													else
													{
														$join = '';
													}
													$where_additional = ' and obj_char_elem_bln_post_initiative_' . $line_count . '.characteristic_element_id = ' . $element->getId();
													break;
												default:
													$join = '';
													break;
											}
											$field_name = 'value';
											$data_type = 'boolean';
											break;
										case 'date':
											switch($item['table_name'])
											{
												case 'company':
													$table_alias = 'obj_char_elem_dte_company_' . $line_count;
													if (!in_array('obj_char_elem_dte_company_' . $line_count, $join_array))
													{
														$join .= 'left join tbl_object_characteristic_elements_date obj_char_elem_dte_company_' . $line_count . ' on obj_char_company_' . $line_count . '.id = obj_char_elem_dte_company_' . $line_count . '.object_characteristic_id';
														$join_array[] = 'obj_char_elem_dte_company_' . $line_count;
													}
													else
													{
														$join = '';
													}
													$where_additional = ' and obj_char_elem_dte_company_' . $line_count . '.characteristic_element_id = ' . $element->getId();
													break;
												case 'post':
													$table_alias = 'obj_char_elem_dte_post_' . $line_count;
													if (!in_array('obj_char_elem_dte_post_' . $line_count,$join_array))
													{
														$join .= 'left join tbl_object_characteristic_elements_date obj_char_elem_dte_post_' . $line_count . ' on obj_char_post_' . $line_count . '.id = obj_char_elem_dte_post_' . $line_count . '.object_characteristic_id';
														$join_array[] = 'obj_char_elem_dte_post_' . $line_count;
													}
													else
													{
														$join = '';
													}
													$where_additional = ' and obj_char_elem_dte_post_' . $line_count . '.characteristic_element_id = ' . $element->getId();
													break;
												case 'post initiative':
													$table_alias = 'obj_char_elem_dte_post_initiative_' . $line_count;
													if (!in_array('obj_char_elem_dte_post_initiative_' . $line_count,$join_array))
													{
														$join .= 'left join tbl_object_characteristic_elements_date obj_char_elem_dte_post_initiative_' . $line_count . ' on obj_char_post_initiative_' . $line_count . '.id = obj_char_elem_dte_post_initiative_' . $line_count . '.object_characteristic_id';
														$join_array[] = 'obj_char_elem_dte_post_initiative_' . $line_count;
													}
													else
													{
														$join = '';
													}
													$where_additional = ' and obj_char_elem_dte_post_initiative_' . $line_count . '.characteristic_element_id = ' . $element->getId();
													break;
												default:
													$join = '';
													break;
											}

											$field_name = 'value';
											$data_type = 'datetime';
											break;
										default:
											break;
									}
								}
								else
								{
									throw new Exception('element ' . $element_name . ' of type ' . $item['table_name'] . ' could not be found');
								}

							}
							else
							{
								// Get the data_type of the characteristic and lookup which tables we need to join
								// NOTE: the $data_type variable set below relate to the data types used in the makeCriteria function
								switch ($characteristic->getDataType())
								{
									case 'text':
										switch($item['table_name'])
										{
											case 'company':
												$table_alias = 'obj_char_txt_company_' . $line_count;
												if (!in_array('obj_char_txt_company_' . $line_count,$join_array))
												{
													$join = 'left join tbl_object_characteristics_text obj_char_txt_company_' . $line_count . ' on c.id = obj_char_txt_company_' . $line_count . '.company_id';
													$join_array[] = 'obj_char_txt_company_' . $line_count;
												}
												else
												{
													$join = '';
												}
												$where_additional = ' and obj_char_txt_company_' . $line_count . '.characteristic_id = ' . $characteristic->getId();
												break;
											case 'post':
												$table_alias = 'obj_char_txt_post_' . $line_count;
												if (!in_array('obj_char_txt_post_' . $line_count,$join_array))
												{
													$join = 'left join tbl_object_characteristics_text obj_char_txt_post_' . $line_count . ' on p.id = obj_char_txt_post_' . $line_count . '.post_id';
													$join_array[] = 'obj_char_txt_post_' . $line_count;
												}
												else
												{
													$join = '';
												}
												$where_additional = ' and obj_char_txt_post_' . $line_count . '.characteristic_id = ' . $characteristic->getId();
												break;
											case 'post initiative':
												$table_alias = 'obj_char_txt_post_initiative_' . $line_count;
												if (!in_array('obj_char_txt_post_initiative_' . $line_count,$join_array))
												{
													$join = 'left join tbl_object_characteristics_text obj_char_txt_post_initiative_' . $line_count . ' on pi.id = obj_char_txt_post_initiative_' . $line_count . '.post_initiative_id';
													$join_array[] = 'obj_char_txt_post_initiative_' . $line_count;
												}
												else
												{
													$join = '';
												}
												$where_additional = ' and obj_char_txt_post_initiative_' . $line_count . '.characteristic_id = ' . $characteristic->getId();
												break;
											default:
												$join = '';
												break;
										}

										$field_name = 'value';
										$data_type = 'string';
										break;
									case 'boolean':
										switch($item['table_name'])
										{
											case 'company':
												$table_alias = 'obj_char_bln_company_' . $line_count;
												if (!in_array('obj_char_bln_company_' . $line_count,$join_array))
												{
													$join = 'left join tbl_object_characteristics_boolean obj_char_bln_company_' . $line_count . ' on c.id = obj_char_bln_company_' . $line_count . '.company_id';
													$join_array[] = 'obj_char_bln_company_' . $line_count;
												}
												else
												{
													$join = '';
												}
												$where_additional = ' and obj_char_bln_company_' . $line_count . '.characteristic_id = ' . $characteristic->getId();
												break;
											case 'post':
												$table_alias = 'obj_char_bln_post_' . $line_count;
												if (!in_array('obj_char_bln_post_' . $line_count,$join_array))
												{
													$join = 'left join tbl_object_characteristics_boolean obj_char_bln_post_' . $line_count . ' on p.id = obj_char_bln_post_' . $line_count . '.post_id';
													$join_array[] = 'obj_char_bln_post_' . $line_count;
												}
												else
												{
													$join = '';
												}
												$where_additional = ' and obj_char_bln_post_' . $line_count . '.characteristic_id = ' . $characteristic->getId();
												break;
											case 'post initiative':
												$table_alias = 'obj_char_bln_post_initiative_' . $line_count;
												if (!in_array('obj_char_bln_post_initiative_' . $line_count,$join_array))
												{
													$join = 'left join tbl_object_characteristics_boolean obj_char_bln_post_initiative_' . $line_count . ' on pi.id = obj_char_bln_post_initiative_' . $line_count . '.post_initiative_id';
													$join_array[] = 'obj_char_bln_post_initiative_' . $line_count;
												}
												else
												{
													$join = '';
												}
												$where_additional = ' and obj_char_bln_post_initiative_' . $line_count . '.characteristic_id = ' . $characteristic->getId();
												break;
											default:
												$join = '';
												break;
										}
										$field_name = 'value';
										$data_type = 'boolean';
										break;
									case 'date':
										switch($item['table_name'])
										{
											case 'company':
												$table_alias = 'obj_char_dte_company_' . $line_count;
												if (!in_array('obj_char_dte_company_' . $line_count,$join_array))
												{
													$join = 'left join tbl_object_characteristics_date obj_char_dte_company_' . $line_count . ' on c.id = obj_char_dte_company_' . $line_count . '.company_id';
													$join_array[] = 'obj_char_dte_company_' . $line_count;
												}
												else
												{
													$join = '';
												}
												$where_additional = ' and obj_char_dte_company_' . $line_count . '.characteristic_id = ' . $characteristic->getId();
												break;
											case 'post':
												$table_alias = 'obj_char_dte_post_' . $line_count;
												if (!in_array('obj_char_dte_post_' . $line_count,$join_array))
												{
													$join = 'left join tbl_object_characteristics_date obj_char_dte_post_' . $line_count . ' on p.id = obj_char_dte_post_' . $line_count . '.post_id';
													$join_array[] = 'obj_char_dte_post_' . $line_count;
												}
												else
												{
													$join = '';
												}
												$where_additional = ' and obj_char_dte_post_' . $line_count . '.characteristic_id = ' . $characteristic->getId();
												break;
											case 'post initiative':
												$table_alias = 'obj_char_dte_post_initiative_' . $line_count;
												if (!in_array('obj_char_dte_post_initiative_' . $line_count,$join_array))
												{
													$join = 'left join tbl_object_characteristics_date obj_char_dte_post_initiative_' . $line_count . ' on pi.id = obj_char_dte_post_initiative_' . $line_count . '.post_initiative_id';
													$join_array[] = 'obj_char_dte_post_initiative_' . $line_count;
												}
												else
												{
													$join = '';
												}
												$where_additional = ' and obj_char_dte_post_initiative_' . $line_count . '.characteristic_id = ' . $characteristic->getId();
												break;
											default:
												$join = '';
												break;
										}

										$field_name = 'value';
										$data_type = 'datetime';
										break;

									default:
										break;
								}

							}
						}
						else
						{
							throw new Exception('characteristic ' . $characteristic_name . ' of type ' . $item['table_name'] . ' could not be found');
						}
						break;

					default:
						break;
				}
			}
			else
			{
				switch ($item['field_name'])
				{
					case 'in post':
						if ($params[0] == 'true')
						{
							$where_temp = " con.first_name != '' and con.surname != '' ";
						}
						else
						{
							$where_temp = " con.first_name = '' and con.surname = '' ";
						}

					default:
						// standard field name
						$field_spec = $spec_array[$item['table_name']][strtolower($item['field_name'])];
						$table_alias = $field_spec['sql_alias'];
						$join = $field_spec['sql_join'];
						$field_name = $field_spec['sql_field'];
						$data_type = $field_spec['sql_data_type'];
						break;
				}


			}

			$operator = $item['operator'];
			$concatenator = $item['concatenator'];
			$bracket_open = $item['bracket_open'];
			$bracket_close = $item['bracket_close'];

			// do we have more than one join (ie $join is an array)
			if (is_array($join))
			{
				foreach ($join as $join_item)
				{
					if (strpos($join_clause, $join_item) === false)
					{
						$join_clause .= $join_item . ' ';
					}
//				    if ($item['field_name'] == 'initiative') {
//                            $join_clause .= ' LEFT JOIN tbl_campaign_companies_do_not_call ccdnc_' . $campaign_companies_do_not_call_count . ' on ccdnc_' . $campaign_companies_do_not_call_count . '.campaign_id = camp.id '; //and c.id = ccdnc_' . $campaign_companies_do_not_call_count . '.company_id ';
//                    }
				}
			}
			else // only single join statement
			{
				if ($join != '')
				{
					if (strpos($join_clause, ' ' . $table_alias . '.') === false)
					{
						$join_clause .= $join . ' ';
					}
				}
//                if ($item['field_name'] == 'initiative') {
//                    $join_clause .= ' LEFT JOIN tbl_campaign_companies_do_not_call ccdnc_' . $campaign_companies_do_not_call_count . ' on ccdnc_' . $campaign_companies_do_not_call_count . '.campaign_id = camp.id '; //and c.id = ccdnc_' . $campaign_companies_do_not_call_count . '.company_id ';
//                }
			}

			// only build a where clause if $table_alias is specified. This is because, as in the case of 'in post'
			// we may want to specify certain definite $where_additional items without having the basic $where_temp populated
			if ($table_alias != '')
			{
				$where_temp = $this->makeCriteria($table_alias, $field_name, $params, $data_type, $operator);
			}
//			else
//			{
//				// need to clean out the $where_temp in case anything left in it from previous iteration
//				$where_temp = '';
//			}

//		    if ($item['field_name'] == 'initiative') {
//                $where_temp .= ' AND ccdnc_' . $campaign_companies_do_not_call_count . '.id IS NULL ';
//                $campaign_companies_do_not_call_count++;
//            }

			// add any additional where clause ($where_additional) and opening and closing brackets
			// NOTE: 10/09/2007 - id $where_additional has been populated then the $where_temp and $where_additional must be bracketed
			// together. This is because if they are used in a statement which uses both 'or' and 'and' then failure to bracket
			// $where_temp and $where_additional together will give erroneous results eg
			// where mi.despatched_date >= '2007-06-19 00:00:00' or (mir.mailer_response_id IN (2) or obj_char_bln_company_3.value = true and obj_char_bln_company_3.characteristic_id = 15)
			if (trim($where_additional) != '' && trim($where_temp) != '')
			{
				$where .= $bracket_open . '(' . trim($where_temp) . $where_additional . ')' . $bracket_close . ' ' . $concatenator . ' ';
			}
			elseif (trim($where_temp) != '')
			{
				$where .= $bracket_open . trim($where_temp) . $where_additional . $bracket_close . ' ' . $concatenator . ' ';
			}
		}

		$where_additional = '';

		$where = trim($where);

		if (strlen($where) > 0)
		{
			// remove any trailing and/or
			$where = 'where ' . rtrim($where, 'andor');
			$main = $this->makeIncludeExcludeSQL($join_clause, $where, $direction, $results_format);
		}

		//echo '<p>Main:' . $main . '</p>';

		return $main;

	}


	/**
	* Assembles the SQL query to be used in the filter builder for constructing the include and exclude user supplied parameters
	* The SQL queries are saved into a class-wide variable (array) ready to be passed to MDB2
	* @param string $join_clause - additional join clauses
	* @param string $where - where clause
	* @param string $direction - whether we are building an include or exclude statement
	*/
	public function makeIncludeExcludeSQL($join_clause, $where, $direction, $results_format)
	{
        $session = Auth_Session::singleton();
        $user = $session->getSessionUser();
        
		$temp_table_name = 't_' . $direction;

		$sql = 'drop table if exists ' .$temp_table_name;
		$sql_array[1] = $sql;

		// NOTE: have used cn_access as the alias in the next section to help ensure that there aren't any conflicts with aliases from the
		//rest of the filter building process
		$join_nbm_access = 'INNER JOIN tbl_campaign_nbms cn_user_access ON camp.id = cn_user_access.campaign_id ';

		// need to have 'c.deleted = 0' and 'p.deleted = 0' as separate items since its posible that the query params
		// don't include any post related params - in which case the 'p.deleted = 0' where clause will exclude any companies
		// which don't have posts
//		$where_deleted = 'AND c.deleted = 0 AND p.deleted = 0 ';

		$where_deleted = 'AND c.deleted = 0 ';

//		if ((boolean)strpos($where, ' p.') === true || (boolean)strpos($where, ' pi.') === true)
//		{
//			$where_deleted .= 'AND p.deleted = 0 ';
//		}
//		echo $where_deleted;
//		echo 'strpos = ' . (boolean)strpos($where, ' pi.');

//		if ((boolean)strpos($where, ' pi.') === true)
//		{
//			$where_deleted .= 'AND p.deleted = 0 ';
//		}

//		echo 'strpos = ' . strpos($where, ' con.');

//		if ((boolean)strpos($where, ' con.') === true)
//		{
//
//			$where_deleted .= 'AND con.deleted = 0 ';
//		}

		$where_nbm_access = 'AND cn_user_access.user_id = ' . self::getCurrentUserId() . ' ' .
							'AND cn_user_access.deactivated_date = \'0000-00-00\' ';

		switch ($results_format)
		{
			case 'Client initiative':
			case 'Client initiative with last note':
			case 'Meeting':
				$sql = 'create temporary table ' .$temp_table_name. ' ( ' .
						'company_id int(11), ' .
						'post_id int(11), ' .
						'post_initiative_id int(11), ' .
				        'meeting_id int(11), ' .
						'key `ix_' .$temp_table_name. '_company_id` (company_id),' .
						'key `ix_' .$temp_table_name. '_post_id` (post_id),' .
						'key `ix_' .$temp_table_name. '_post_initiative_id` (post_initiative_id), ' .
				        'key `ix_' .$temp_table_name. '_meeting_id` (meeting_id)' .
						')';
// 						') TYPE= InnoDB';

				$sql_array[2] = $sql;

				$sql = 'insert into ' . $temp_table_name .' select distinct c.id as company_id, p.id as post_id, ' .
						'pi.id as post_initiative_id, m.id as meeting_id ' .
						'from tbl_companies c ' .
						'left join tbl_sites s on c.id = s.company_id ' .
						'left join tbl_posts p on c.id = p.company_id and p.deleted = 0 ' .
						'left join tbl_contacts con on con.post_id = p.id and con.deleted = 0 ' .
						'left join tbl_post_initiatives pi on p.id = pi.post_id ' .
				        'left join tbl_meetings m on m.post_initiative_id = pi.id ' .
						'left join tbl_initiatives i on i.id = pi.initiative_id ' . // nbm access rights
						'left join tbl_campaigns camp on camp.id = i.campaign_id ' . // nbm access rights
						$join_clause . ' ' .
						$join_nbm_access . ' ' .
                        $where . ' ' .
                        (!empty($user['client_id']) 
                            ? (!empty($where) ? 'AND' : 'WHERE') . ' p.data_owner_id = ' . $user['client_id'] . ' '
                            : (!empty($where) ? 'AND' : 'WHERE') . ' p.data_owner_id IS NULL ') .
						$where_deleted . ' ' .
						$where_nbm_access;

				break;
			case 'Site':
			case 'Company':
			case 'Site and posts':
			case 'Company and posts':
			case 'Mailer':
				$sql = 'create temporary table ' .$temp_table_name. ' ( ' .
						'company_id int(11), ' .
						'post_id int(11), ' .
						'key `ix_' .$temp_table_name. '_company_id` (company_id),' .
						'key `ix_' .$temp_table_name. '_post_id` (post_id)' .
						')';
// 						') TYPE= InnoDB';
				$sql_array[2] = $sql;

				// if $where contains 'pi' then need to restrict access to clients to which the nbm has access
				// - ie, include  $join_nbm_access and $where_nbm_access
				if (strpos($where, 'pi.') === false)
				{
					$sql = 'insert into ' . $temp_table_name .' select distinct c.id as company_id, p.id as post_id ' .
						'from tbl_companies c ' .
						'left join tbl_sites s on c.id = s.company_id ' .
						'left join tbl_posts p on c.id = p.company_id and p.deleted = 0 ' .
						'left join tbl_contacts con on con.post_id = p.id and con.deleted = 0 ' .
						'left join tbl_post_initiatives pi on p.id = pi.post_id ' .
						'left join tbl_meetings m on m.post_initiative_id = pi.id ' .
						$join_clause . ' ' .
                        $where . ' ' .
                        (!empty($user['client_id']) 
                            ? (!empty($where) ? 'AND' : 'WHERE') . ' p.data_owner_id = ' . $user['client_id'] . ' '
                            : (!empty($where) ? 'AND' : 'WHERE') . ' p.data_owner_id IS NULL ') .
						$where_deleted;
				}
				else
				{
					$sql = 'insert into ' . $temp_table_name .' select distinct c.id as company_id, p.id as post_id ' .
						'from tbl_companies c ' .
						'left join tbl_sites s on c.id = s.company_id ' .
						'left join tbl_posts p on c.id = p.company_id and p.deleted = 0 ' .
						'left join tbl_contacts con on con.post_id = p.id and con.deleted = 0 ' .
						'left join tbl_post_initiatives pi on p.id = pi.post_id ' .
						'left join tbl_meetings m on m.post_initiative_id = pi.id ' .
						'left join tbl_initiatives i on i.id = pi.initiative_id ' . // nbm access rights
						'left join tbl_campaigns camp on camp.id = i.campaign_id ' . // nbm access rights
						$join_clause . ' ' .
						$join_nbm_access . ' ' .
                        $where . ' ' .
                        (!empty($user['client_id']) 
                            ? (!empty($where) ? 'AND' : 'WHERE') . ' p.data_owner_id = ' . $user['client_id'] . ' '
                            : (!empty($where) ? 'AND' : 'WHERE') . ' p.data_owner_id IS NULL ') .
						$where_deleted . ' ' .
						$where_nbm_access;
				}

				break;
//			case 'Mailer': // agreed with Rob Anning on 04/10/07 that mailer queries should not exclude deleted posts. This is because deleted
//							// posts may hold some information (eg have been mailed in 2007) which may cause unexpected results. Eg if Rob wants to
//							// exclude any companies which have been mailed in 2007 then if a deleted post has been mailed in 2007 this post will
//							// not be 'excluded' from the results since it would not be included in the 'exclude' part of the filter!
//
//				$sql = 'create temporary table ' .$temp_table_name. ' ( ' .
//						'company_id int(11), ' .
//						'post_id int(11), ' .
//						'post_initiative_id int(11), ' .
//						'key `ix_' .$temp_table_name. '_company_id` (company_id),' .
//						'key `ix_' .$temp_table_name. '_post_id` (post_id),' .
//						'key `ix_' .$temp_table_name. '_post_initiative_id` (post_initiative_id)' .
//						') TYPE= InnoDB';
//
//				$sql_array[] = $sql;
//
//				// NOTE: In the following query we need to keep the aliases for the tables the same as for the views in the previous case
//				// statements. This is because these aliases are refered to further down the script - so changing them messes up the
//				// final SQL statement which is executed.
//				$sql = 'insert into ' . $temp_table_name .' select distinct c.id as company_id, p.id as post_id, ' .
//						'pi.id as post_initiative_id ' .
//						'from tbl_companies c ' .
//						'left join tbl_sites s on c.id = s.company_id ' .
//						'left join tbl_posts p on c.id = p.company_id ' .
//						'left join tbl_contacts con on con.post_id = p.id ' .
//						'left join tbl_post_initiatives pi on p.id = pi.post_id ' .
//						'left join tbl_initiatives i on i.id = pi.initiative_id ' . // nbm access rights
//						'left join tbl_campaigns camp on camp.id = i.campaign_id ' . // nbm access rights
//						$join_clause . ' ' .
//						$join_nbm_access . ' ' .
//						$where . ' ' .
//						$where_nbm_access;
//
//				break;
			default:
				var_dump($results_format);
				die;
				throw new Exception('No results format variable ($results_format) supplied.');
				break;
		}

		$sql_array[3] = $sql;

		switch ($direction)
		{
			case 'include':
				$this->sql_include_query = $sql_array;
				break;
			case 'exclude':
				$this->sql_exclude_query = $sql_array;
				break;
			default:
				return false;
		}

	}


	/**
	* Assembles the main SQL query to be used in the filter builder. This is all standard SQL so no input params
	* The SQL queries are saved into a class-wide variable (array) ready to be passed to MDB2
	*/
	public function makeMainSQL($filter_id, $display_results=true)
	{
		$debug = false;
// 		$debug = true;

		$t = '';

		$filter = app_domain_Filter::find($filter_id);
		$results_format = $filter->getResultsFormat();

		$finder = self::getFinder(__CLASS__);

		// $t is used to build up a string of the queries being executed.
		// TODO: remove at run time
		foreach ($this->sql_include_query as $item)
		{
			$t .= $item . '; ';
		}

		// debug
		if (!$debug)
		{
			// Execute any sql to create the include temp table
			$finder->doExecuteSqlArray($this->sql_include_query);
		}

		// If there's no exclude query (determined by length of $this->sql_exclude_query) then don't need to create an exclude temp table or
		// do any joins between the include/exclude temp tables
		if (count($this->sql_exclude_query) > 0)
		{
			// debug
			if (!$debug)
			{
				// Execute any sql to create the include temp table
				$finder->doExecuteSqlArray($this->sql_exclude_query);
			}

			foreach ($this->sql_exclude_query as $item)
			{
				$t .= $item . '; ';
			}

			switch ($results_format)
			{

				case 'Client initiative':
				case 'Client initiative with last note':
				case 'Meeting':
					$sql = 	'delete ti.* from t_include ti join t_exclude te on ' .
							'ti.company_id = te.company_id and ti.post_id = te.post_id and ti.post_initiative_id = te.post_initiative_id';
					$this->sql_query[] = $sql;

					$sql = 	'delete ti.* from t_include ti join t_exclude te on ' .
							'ti.company_id = te.company_id and ti.post_id = te.post_id where te.post_initiative_id is null';
					$this->sql_query[] = $sql;

					$sql = 	'delete ti.* from t_include ti join t_exclude te on ' .
							'ti.company_id = te.company_id where te.post_id is null and te.post_initiative_id is null';
					$this->sql_query[] = $sql;
					break;

				case 'Company':
				case 'Site':
				case 'Company and posts':
				case 'Site and posts':
				case 'Mailer':
					$sql = 	'delete ti.* from t_include ti join t_exclude te on ' .
							'ti.company_id = te.company_id and ti.post_id = te.post_id';
					$this->sql_query[] = $sql;

					$sql = 	'delete ti.* from t_include ti join t_exclude te on ' .
							'ti.company_id = te.company_id where te.post_id is null';
					$this->sql_query[] = $sql;

					$sql = 	'delete ti.* from t_include ti join t_exclude te on ' .
							'ti.company_id = te.company_id';
					$this->sql_query[] = $sql;
					break;
			}

			if (!$debug)
			{
				$finder->doExecuteSqlArray($this->sql_query);
			}


        }
        
        $session = Auth_Session::singleton();
        $user = $session->getSessionUser();

		// The next SQL sets up a table with a row for each company/post combo in t_include.
		// This is because otherwise our post/company counts and comm counts become skewed if
		// we have the post_initiative_id field in t_include - because this duplicates the post
		// information for as many rows as there are initiative_ids associated with that post
		$sql = 'create temporary table t_include_count ( ' .
						'company_id int(11), ' .
						'post_id int(11), ' .
						'key `ix_t_include_count_company_id` (company_id),' .
						'key `ix_t_include_count_post_id` (post_id)' .
						')';
// 						') TYPE= InnoDB';
		$this->sql_query[0] = $sql;

		$sql =	'insert into t_include_count ' .
				'select company_id, post_id ' .
				'from t_include ' .
				'group by company_id, post_id';
		$this->sql_query[1] = $sql;

		$sql = 'create temporary table t_include_post_count ( ' .
						'company_id int(11), ' .
						'post_id int(11), ' .
						'post_count int(11), ' .
						'propensity_sum int(11), ' .
						'propensity_max int(11), ' .
						'propensity_avg int(11), ' .
						'propensity_min int(11), ' .
						'key `ix_t_include_count_company_id` (company_id), ' .
						'key `ix_t_include_count_post_id` (post_id), ' .
						'key `ix_t_include_count_post_count` (post_count), ' .
						'key `ix_t_include_count_propensity_sum` (propensity_sum), ' .
						'key `ix_t_include_count_propensity_max` (propensity_max), ' .
						'key `ix_t_include_count_propensity_avg` (propensity_avg), ' .
						'key `ix_t_include_count_propensity_min` (propensity_min) ' .
						')';
// 						') TYPE= InnoDB';
		$this->sql_query[2] = $sql;

		$sql =	'insert into t_include_post_count ' .
				'select p.company_id, p.id as post_id, count(ti.post_id) as post_count, ' .
				'sum(p.propensity) as propensity_sum, max(p.propensity) as propensity_max, avg(p.propensity) as propensity_avg, ' .
				'min(p.propensity) as propensity_min ' .
				'from t_include_count ti ' .
                'left join tbl_posts p on ti.post_id = p.id ' .
                (!empty($user['client_id']) 
                    ? 'WHERE p.data_owner_id = ' . $user['client_id'] . ' '
                    : 'WHERE p.data_owner_id IS NULL ') .
				'group by ti.company_id';

		$this->sql_query[3] = $sql;

		// make temporary stats tables
		$sql = 	'create temporary table t_filter_stats ' .
				'select ' .
				'count(distinct company_id) as company_count, ' .
				'count(distinct post_id) as post_count ' .
				'from t_include_count';
		$this->sql_query[4] = $sql;

		$sql = 'update tbl_filters f, t_filter_stats fs ' .
				'set ' .
				'f.company_count = fs.company_count, ' .
				'f.post_count = fs.post_count ' .
				'where f.id = ' . $filter_id;

		$this->sql_query[5] = $sql;

		$sql =	'delete from tbl_filter_results ' .
				'where filter_id = ' . $filter_id;
		$this->sql_query[6] = $sql;


		// insert into tbl_filter_results - switch on results_format as different formats will have different columns in t_include
		switch ($results_format)
		{
			case 'Client initiative':
			case 'Client initiative with last note':
			case 'Meeting':
				$sql = 	'insert into tbl_filter_results ' .
				'(filter_id, company_id, post_id, post_initiative_id, meeting_id, propensity_sum, propensity_max, propensity_avg, propensity_min, ' .
				'post_count) ' .
				'select ' .	$filter_id . ', ti.company_id, ti.post_id, post_initiative_id, meeting_id, propensity_sum, propensity_max, propensity_avg, ' .
				'propensity_min, post_count ' .
				'from t_include ti ' .
				'left join t_include_post_count tipc on ti.company_id = tipc.company_id';
				break;
			case 'Company':
			case 'Site':
			case 'Company and posts':
			case 'Site and posts':
			case 'Mailer':
				$sql = 	'insert into tbl_filter_results ' .
				'(filter_id, company_id, post_id, propensity_sum, propensity_max, propensity_avg, propensity_min, ' .
				'post_count) ' .
				'select ' . $filter_id . ', ti.company_id, ti.post_id, propensity_sum, propensity_max, propensity_avg, propensity_min, ' .
				'post_count ' .
				'from t_include ti ' .
				'left join t_include_post_count tipc on ti.company_id = tipc.company_id';
				break;
			default:
				throw new Exception('No results format variable ($results_format) supplied.');
				break;

		}
		$this->sql_query[7] = $sql;

// 		echo '<pre>';
// 		print_r($this->sql_query);
// 		echo '</pre>';
		if (!$debug)
		{
			$finder->doExecuteSqlArray($this->sql_query);
		}

		if ($debug)
		{
			foreach ($this->sql_query as $item)
			{
				$t .= $item . '; ';
			}
		}

		// remove temporary tables
		$this->sql_query = array();
		$sql = 'drop table if exists t_include';
		$this->sql_query[] = $sql;
		$sql = 'drop table if exists t_exclude';
		$this->sql_query[] = $sql;
		$sql = 'drop table if exists t_include_post_count';
		$this->sql_query[] = $sql;
		$sql = 'drop table if exists t_include_count';
		$this->sql_query[] = $sql;
		$sql = 'drop table if exists t_filter_stats';
		$this->sql_query[] = $sql;

		if (!$debug)
		{
			$finder->doExecuteSqlArray($this->sql_query);
		}

		$return = array();

		if ($display_results)
		{
			if (!$debug)
			{
				$results = $finder->getFilterBuilderResults($filter_id, $results_format);
			}

			if ($debug)
			{
				foreach ($this->sql_query as $item)
				{
					$t .= $item . '; ';
				}
				$return['query'] = $t;
			}
			else
			{
				$return['results'] = $results;
			}

		}

		return $return;

	}

	/**
	* Builds a string criteria to be used as a sql 'where' clause
	* @param string $tbl_alias - name or alias of the table being queried
	* @param string $fld_name - name of field being queried
	* @param string $params - array of data to be used in the where clause
	* @param boolean $data_type - type of field data being queried
	* @param string $operator - operator being used in the query - eg =, in, like etc
	*/
	protected function makeCriteria($tbl_alias, $fld_name, $params, $data_type, $operator)
	{
		$temp='';

		switch ($operator)
		{
			case "in":

				foreach ($params as $param)
				{
					//echo $param . "<br>";
					if ($data_type == 'string')
					{
						$temp .= "'" . $param . "',";
					}
					else
					{
						$temp .= $param . ",";
					}
				}

				//remove trailing comma and add brackets
				$temp = $tbl_alias . '.' . $fld_name . " IN (" . substr($temp,0,strlen($temp)-1). ")";
				break;
			case "is":
				$temp = $tbl_alias . '.' . $fld_name . " IS " . $params[0];
				break;
			//The following four cases are designed to handle either datetime fields
			//(where a date and a time is held in the same field) or
			//date only fields.
			// Todo: Add validation
			case "between_date":
				$temp = $tbl_alias . '.' . $fld_name . " BETWEEN '" . Utils::DateFormat($params[0],'DD/MM/YYYY') . "' AND '" . $params[1] . "'";
				break;

			case "between_datetime":
				$temp = $tbl_alias . '.' . $fld_name . " BETWEEN '" . Utils::DateFormat($params[0],'DD/MM/YYYY') . " 00:00:00' AND '" . $params[1] . " 23:59:59'";
				break;

			case "since (and including)":
				if (strtolower($params[0]) == 'today')
				{
					$temp = $tbl_alias . '.' . $fld_name . " >= concat(CURDATE() , ' 00:00:00')";
				}
				else
				{
					$temp = $tbl_alias . '.' . $fld_name . " >= '" . Utils::DateFormat($params[0],'DD/MM/YYYY', 'YYYY-MM-DD') . " 00:00:00'";
				}
				break;

			case "since (and not including)":
				if (strtolower($params[0]) == 'today')
				{
					$temp = $tbl_alias . '.' . $fld_name . " > concat(CURDATE() , ' 00:00:00')";
				}
				else
				{
					$temp = $tbl_alias . '.' . $fld_name . " > '" . Utils::DateFormat($params[0],'DD/MM/YYYY', 'YYYY-MM-DD') . " 00:00:00'";
				}
				break;

			case "before (and including)":
				if (strtolower($params[0]) == 'today')
				{
					$temp = $tbl_alias . '.' . $fld_name . " <= concat(CURDATE() , ' 23:59:59')";
				}
				else
				{
					$temp = $tbl_alias . '.' . $fld_name . " <= '" . Utils::DateFormat($params[0],'DD/MM/YYYY', 'YYYY-MM-DD') . " 23:59:59'";
				}
				break;

			case "before (and not including)":
				if (strtolower($params[0]) == 'today')
				{
					$temp = $tbl_alias . '.' . $fld_name . " < concat(CURDATE() , ' 23:59:59')";
				}
				else
				{
					$temp = $tbl_alias . '.' . $fld_name . " < '" . Utils::DateFormat($params[0],'DD/MM/YYYY', 'YYYY-MM-DD') . " 23:59:59'";
				}
				break;

			case "contains":

				$temp = $tbl_alias . '.' . $fld_name . ' ';

				foreach ($params as $param)
				{
					if ($data_type == 'string')
					{
						$temp .= "LIKE '%";
					}
					else
					{
						$temp .= "LIKE %";
					}
					$temp .= trim($param) . "%";

					if ($data_type == 'string')
					{
						$temp .= "'";
					}

					$temp .= " OR " . $fld_name . " ";
				}

				//trim trailing OR
//				$temp = substr($temp,0,strlen($temp)-(strlen($fld_name) + 5)). ")";
				$temp = substr($temp,0,strlen($temp)-(strlen($fld_name) + 4));

				break;

			case "starts with":

//				$temp = " (" . $tbl_alias . '.' . $fld_name . ' ';
				$temp = $tbl_alias . '.' . $fld_name . ' ';

				foreach ($params as $param)
					{
						if ($data_type == 'string')
					{
						$temp .= " LIKE '" . $param . "%";
					}
					else
					{
						$temp .= " LIKE " . $param . "%";
					}

					if ($data_type == 'string')
					{
						$temp .= "'";
					}
					$temp .= " OR " . $fld_name . " ";
				}

				//trim trailing OR
//				$temp = substr($temp,0,strlen($temp)-(strlen($fld_name) + 5)). ")";
				$temp = substr($temp,0,strlen($temp)-(strlen($fld_name) + 4));

				break;
			case "=":
			case "equals":
//				$temp = " (" . $tbl_alias . '.' . $fld_name . ' ';
				$temp = $tbl_alias . '.' . $fld_name . ' ';

				foreach ($params as $param)
					{
						if ($data_type == 'string')
					{
						$temp .= " = '" . $param;
					}
					else
					{
						$temp .= " = " . $param;
					}

					if ($data_type == 'string')
					{
						$temp .= "'";
					}
					$temp .= " OR " . $fld_name . " ";
				}

				//trim trailing OR
//				$temp = substr($temp,0,strlen($temp)-(strlen($fld_name) + 5)). ")";
				$temp = substr($temp,0,strlen($temp)-(strlen($fld_name) + 4));

				break;

			case ">=":
			case 'greater than or equal to';
				$temp = $tbl_alias . '.' . $fld_name . " >= '" . $params[0] ."'";
				break;

			case "<=":
			case 'less than or equal to';
				$temp = $tbl_alias . '.' . $fld_name . " <= '" . $params[0] . "'";
				break;


			default:
				break;
		}



		return $temp;
	}



	/**
	 * get display results for a filter_id
	 * @param integer $filter_id - id of the filter to display
	 * @param string $results_format - format of the display results
	 * @return array of raw filter builder mapper data
	 */
	public static function getFilterBuilderResults($filter_id, $results_format)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getFilterBuilderResults($filter_id, $results_format);
	}


	/**
	 * get export results for a filter_id
	 * @param integer $filter_id - id of the filter to display
	 * @param string $results_format - format of the display results
	 * @return array of raw filter builder mapper data
	 */
	public static function getFilterExport($filter_id, $results_format)
	{
		$finder = self::getFinder(__CLASS__);
		return $finder->getFilterExport($filter_id, $results_format);
	}


}



?>