<?php

/**
 * Defines the app_domain_ReportReader class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReaderObject.php');
require_once('app/mapper/ReportReaderMapper.php');

/**
 * @package Alchemis
 */
class app_domain_ReportReader extends app_domain_ReaderObject
{
	/**
	 * By declaring private, we prevent instatiation by other objects.
	 */
	protected function __construct()
	{
		parent::__construct();
	}

	/**
	 * Returns the active reports.
	 * @return array associative array of reports
	 */
	public static function findAll()
	{
		$reader = self::getReader(__CLASS__);
		return $reader->findAll();
	}

	/**
	 * Returns the details for a given report.
	 * @param integer $id
	 * @return array assoicative array mapping to a given report record
	 */
	public static function find($id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->find($id);
	}

	/**
	 * Returns the timestamp of when the data statistics where last compiled.
	 * @return string timestamp
	 */
	public static function getDataStatisticsLastRun()
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getDataStatisticsLastRun();
	}

	/**
	 * Returns the data for report 1.
	 * @param string $year_month in the format 'YYYYMM'
	 * @param integer $user_id restrict to a given user
	 * @param array $nbm_exclusions list of NBM IDs to exclude
	 * @return array associative array mapping to a given report record
	 */
	public static function getReport1MainData($year_month, $user_id, $nbm_exclusions = null, $client_id = null)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport1MainData($year_month, $user_id, $nbm_exclusions, $client_id);
	}

	/**
	 * Returns the holiday data used in Report1.
	 * @param string $year_month in the format 'YYYYMM'
	 * @param integer $user_id
	 * @return array assoicative array mapping to a given report record
	 */
	public static function getReport1HolidayData($year_month, $user_id, $client_id = null)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport1HolidayData($year_month, $user_id, $client_id);
	}

	/**
	 * Get a list of the new starter for a given month.
	 * @param string $year_month in the format 'YYYYMM'
	 * @param return where each item is an NBM name
	 */
	public static function getNewStarters($year_month, $user_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getNewStarters($year_month, $user_id);
	}

	/**
	 * Returns the data for report 2.
	 * @param string $start in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param string $end in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param array $nbm_exclusions list of NBM IDs to exclude
	 * @return array associative array mapping to a given report record
	 */
	public static function getReport2Data($start, $end, $nbm_exclusions = null, $client_id = null)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport2Data($start, $end, $nbm_exclusions, $client_id);
	}

//	/**
//	 * Returns the details for a given report.
//	 * @param integer $id
//	 * @return array assoicative array mapping to a given report record
//	 */
//	public static function getReport3Data()
//	{
//		$reader = self::getReader(__CLASS__);
//		return $reader->getReport3Data();
//	}

	/**
	 * Get the summary data for Report 3.
	 * @param string $start date in the format 'YYYY-MM-DD'
	 * @param integer $user_id
	 * @return array
	 */
	public static function getReport3SummaryData($start, $user_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport3SummaryData($start, $user_id);
	}

	/**
	 * Get the detail data for Report 3.
	 * @param string $start date in the format 'YYYY-MM-DD'
	 * @param integer $user_id
	 * @return array
	 */
	public static function getReport3DetailData($start, $user_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport3DetailData($start, $user_id);
	}

	/**
	 * Get the summary data for Report 5.
	 * @param string $start date in the format 'YYYY-MM-DD'
	 * @param string $end date in the format 'YYYY-MM-DD'
	 * @param integer $client_id
	 * @param boolean $all_statuses whether to show all status, even if no calls logged against them
	 * @return array
	 */
	public static function getReport5SummaryData($start, $end, $client_id, $project_ref, $all_statuses = false)
	{
		$start = $start . ' 00:00:00';
		$end   = $end . ' 23:59:59';

		$reader = self::getReader(__CLASS__);
		return $reader->getReport5SummaryData($start, $end, $client_id, $project_ref, $all_statuses);
	}

	/**
	 * Get the detail data for Report 5.
	 * @param string  $start        start date in the format 'YYYY-MM-DD'
	 * @param string  $end          end date in the format 'YYYY-MM-DD'
	 * @param integer $client_id    the client ID
	 * @param string  $project_ref
	 * @param integer $effectives   whether to show (1) effectives, (2) non-effectives, or (3) both
	 * @param boolean $full_history whether to include the full history of effective notes
	 * @return array
	 */
	public static function getReport5DetailData($start, $end, $client_id, $project_ref, $effectives, $full_history = true)
	{
		$start = $start . ' 00:00:00';
		$end   = $end . ' 23:59:59';

		$reader = self::getReader(__CLASS__);
		$rows = $reader->getReport5DetailData($start, $end, $client_id, $project_ref, $effectives);

//		echo '<pre>';
//		print_r($rows);
//		echo '</pre>';
//		exit;

		foreach ($rows as &$row)
		{
			$company = app_domain_Company::find($row['company_id']);
			$row['address']    = $company->getSiteAddress();
			$row['calls']      = app_domain_Post::countCallsInPeriod($row['post_id'], $start, $end, $client_id);
			$row['effectives'] = app_domain_Post::countEffectivesInPeriod($row['post_id'], $start, $end, $client_id);
			if ($full_history)
			{
				$history = self::getReport5FullNotesHistory($row['post_initiative_id'], $row['date'], $effectives);
				if ($history)
				{
					$row['note_history'] = $history;
				}
			}
		}
		return $rows;
	}

	/**
	 * Get the full effective note history for a post initiative before a given date.
	 * @param string  $post_initiative_id
	 * @param string  $date               in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param integer $effectives         whether to show (1) effectives, (2) non-effectives, or (3) both
	 * @return array
	 */
	public static function getReport5FullNotesHistory($post_initiative_id, $date, $effectives)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport5FullNotesHistory($post_initiative_id, $date, $effectives);
	}

	/**
	 * Returns the data for report 6.
	 * @param string $start in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param string $end in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param integer $order_by
	 * @return array assoicative array mapping to a given report record
	 */
	public static function getReport6Data($start, $end, $order_by = 0, $client_id = null)
	{
		$reader = self::getReader(__CLASS__);
//		echo $client_id;
		return $reader->getReport6Data($start, $end, $order_by, $client_id);
	}

	/**
	 * Returns the data for report 7.
	 * @param string $start in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param string $end in the format 'YYYY-MM-DD HH:MM:SS'
	 * @param integer $client_id client id
	 * @return array associative array mapping to a given report record
	 */
	public static function getReport7Data($start, $end, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7Data($start, $end, $client_id);
	}

	public static function getReport7ClientCampaignSummary($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7ClientCampaignSummary($start_date, $end_date, $client_id);
	}

	public static function getReport7ClientCampaignDisciplines($client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7ClientCampaignDisciplines($client_id);
	}

	public static function getReport7ClientCampaignLeadNbm($client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7ClientCampaignLeadNbm($client_id);
	}

	public static function getReport7ClientCampaignNonLeadNbm($client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7ClientCampaignNonLeadNbm($client_id);
	}

	public static function getReport7MeetingsTargets($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7MeetingsTargets($start_date, $end_date, $client_id);
	}

	public static function getReport7DatabaseAnalysisProspect($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7DatabaseAnalysisProspect($start_date, $end_date, $client_id);
	}

	public static function getReport7MeetingsTargetsByNbm($start_date, $end_date, $client_id, $user_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7MeetingsTargetsByNbm($start_date, $end_date, $client_id, $user_id);
	}

	public static function getReport7DatabaseAnalysisProspectByNbm($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7DatabaseAnalysisProspectByNbm($start_date, $end_date, $client_id);
	}

	public static function getReport7DatabaseAnalysisProspectByNbmByMonth($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7DatabaseAnalysisProspectByNbmByMonth($start_date, $end_date, $client_id);
	}

	public static function getReport7MeetingsSetSummaryNewMeetings($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7MeetingsSetSummaryNewMeetings($start_date, $end_date, $client_id);
	}

	public static function getReport7MeetingsSetSummaryRearrangedMeetings($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7MeetingsSetSummaryRearrangedMeetings($start_date, $end_date, $client_id);
	}

	public static function getReport7CampaignCancellationsMeetingLeadTimes($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7CampaignCancellationsMeetingLeadTimes($start_date, $end_date, $client_id);
	}

	public static function getReport7PeriodCancellationsMeetingLeadTimes($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7PeriodCancellationsMeetingLeadTimes($start_date, $end_date, $client_id);
	}

	public static function getReport7CancellationsMeetingLeadTimesByCompany($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7CancellationsMeetingLeadTimesByCompany($start_date, $end_date, $client_id);
	}

	public static function getReport7DatabaseAnalysis($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7DatabaseAnalysis($start_date, $end_date, $client_id);
	}

	public static function getReport7DatabaseAnalysisByCompany($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7DatabaseAnalysisByCompany($start_date, $end_date, $client_id);
	}

	public static function getReport7DatabaseAnalysisProspectsNotYetAttempted($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7DatabaseAnalysisProspectsNotYetAttempted($start_date, $end_date, $client_id);
	}

	public static function getReport7DatabaseAnalysisCompaniesNotYetAttempted($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7DatabaseAnalysisCompaniesNotYetAttempted($start_date, $end_date, $client_id);
	}

	public static function getReport7LeadNBMEffectiveAnalysis($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7LeadNBMEffectiveAnalysis($start_date, $end_date, $client_id);
	}

	public static function getReport7LeadNBMDisciplineAnalysis($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7LeadNBMDisciplineAnalysis($start_date, $end_date, $client_id);
	}

	public static function getReport7LeadNBMSectorAnalysis($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7LeadNBMSectorAnalysis($start_date, $end_date, $client_id);
	}

	public static function getReport7Pipeline($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport7Pipeline($start_date, $end_date, $client_id);
	}

	public static function getReport8KeyToTermsAll($start_date, $end_date, $client_id, $filter_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport8KeyToTermsAll($start_date, $end_date, $client_id, $filter_id);
	}

	public static function getReport8KeyToTermsOnlyCommunications($start_date, $end_date, $client_id, $filter_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport8KeyToTermsOnlyCommunications($start_date, $end_date, $client_id, $filter_id);
	}

	public static function getReport8CampaignSummary($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport8CampaignSummary($start_date, $end_date, $client_id);
	}

	public static function getReport8PeriodSummary($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport8PeriodSummary($start_date, $end_date, $client_id);
	}

	public static function getReport8SectorPenetrationSummary($start_date, $end_date, $client_id)
	{
		$reader = self::getReader(__CLASS__);
		if (isset($_GET['pdf']) && $_GET['pdf'] == 2) {
			echo '<pre>';
			echo __CLASS__.'</br>';
			echo $start_date.'</br>';
			echo $end_date.'</br>';
			echo $client_id.'</br>';
			print_r($reader->getReport8SectorPenetrationSummary($start_date, $end_date, $client_id));
			echo '</pre>';
		}
		return $reader->getReport8SectorPenetrationSummary($start_date, $end_date, $client_id);
	}

	public static function getReport8PeriodResults($start_date, $end_date, $client_id, $filter_id)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getReport8PeriodResults($start_date, $end_date, $client_id, $filter_id);
	}

    public static function getReport10GlobalSectorAnalysis($start_date, $end_date)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport10GlobalSectorAnalysis($start_date, $end_date);
    }

    public static function getReport10NbmSectorAnalysis($start_date, $end_date)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport10NbmSectorAnalysis($start_date, $end_date);
    }


    public static function getReport11GlobalDisciplineAnalysis($start_date, $end_date)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport11GlobalDisciplineAnalysis($start_date, $end_date);
    }

    public static function getReport11NbmDisciplineAnalysis($start_date, $end_date)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport11NbmDisciplineAnalysis($start_date, $end_date);
    }


    public static function getReport12GlobalSectorDisciplineAnalysis($start_date, $end_date)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport12GlobalSectorDisciplineAnalysis($start_date, $end_date);
    }


    public static function getReport13QuarterSummary($year, $nbm_exclusions, $client_id = null)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport13QuarterSummary($year, $nbm_exclusions, $client_id);
    }

    public static function getReport13QuarterClientSummary($year, $nbm_exclusions, $client_id = null)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport13QuarterClientSummary($year, $nbm_exclusions, $client_id);
    }


    public static function getReport14NBMList($nbm_exclusions, $client_id = null)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport14NBMList($nbm_exclusions, $client_id);
    }

    public static function getReport14SingleNBMList($nbm_id)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport14SingleNBMList($nbm_id);
    }

    public static function getReport14NBMSummary($year, $userId)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport14NBMSummary($year, $userId);
    }

    public static function getReport14QuarterClientSummary($year, $userId)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport14QuarterClientSummary($year, $userId);
    }


//    public static function getReport15ClientList($clientId)
//    {
//        $reader = self::getReader(__CLASS__);
//        return $reader->getReport15ClientList($clientId);
//    }

    public static function getReport15ClientExceptionBase($clientId)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport15ClientExceptionBase($clientId);
    }

    public static function getReport15ClientMeetings($clientId)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport15ClientMeetings($clientId);
    }

    public static function getReport15ClientFreshLeads($clientId)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport15ClientFreshLeads($clientId);
    }

    public static function getReport15ClientMaxTargetDate($clientId)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport15ClientMaxTargetDate($clientId);
    }

    public static function getReport15ClientSectorCount($clientId)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport15ClientSectorCount($clientId);
    }

    public static function getReport15ClientDisciplineCount($clientId)
    {
        $reader = self::getReader(__CLASS__);
        return $reader->getReport15ClientDisciplineCount($clientId);
    }

	///////////////////////////////////////////////////////////




	/**
	 * Get the target number of calls made.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public static function getTargetCalls($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getTargetCalls($start_date, $end_date, $team_id, $nbm_id);
	}

	/**
	 * Get the target number of effective calls made.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public static function getTargetEffectives($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getTargetEffectives($start_date, $end_date, $team_id, $nbm_id);
	}



	/**
	 * Get the target number of meetings set in a given period.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public static function getTargetMeetingsSet($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getTargetMeetingsSet($start_date, $end_date, $team_id, $nbm_id);
	}

	/**
	 * Get the target number of meetings attended in a given period.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public static function getTargetMeetingsAttended($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getTargetMeetingsAttended($start_date, $end_date, $team_id, $nbm_id);
	}

	/**
	 * Get the actual number of calls made in a given period.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @param string $campaign_id
	 * @return integer
	 */
	public static function getActualCalls($start_date, $end_date, $team_id = null, $nbm_id = null, $campaign_id = null)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getActualCalls($start_date, $end_date, $team_id, $nbm_id, $campaign_id);
	}

	/**
	 * Get the actual number of effective calls made in a given period.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @param string $campaign_id
	 * @return integer
	 */
	public static function getActualEffectives($start_date, $end_date, $team_id = null, $nbm_id = null, $campaign_id = null)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getActualEffectives($start_date, $end_date, $team_id, $nbm_id, $campaign_id);
	}

	/**
	 * Get the actual number of meetings set in a given period.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @param string $campaign_id
	 * @return integer
	 */
	public static function getActualMeetingsSet($start_date, $end_date, $team_id = null, $nbm_id = null, $campaign_id = null)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getActualMeetingsSet($start_date, $end_date, $team_id, $nbm_id, $campaign_id);
	}

	/**
	 * Get the actual number of meetings attended in a given period.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @param string $campaign_id
	 * @return integer
	 */
	public static function getActualMeetingsAttended($start_date, $end_date, $team_id = null, $nbm_id = null, $campaign_id = null)
	{
		$reader = self::getReader(__CLASS__);
		return $reader->getActualMeetingsAttended($start_date, $end_date, $team_id, $nbm_id, $campaign_id);
	}

	/**
	 * Get the number of calls required to meet the end of month target.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public static function getRequiredCalls($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		// Work out last day of the month for the period end month
		$month_end_date = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($end_date)) + 1, 0, date('Y', strtotime($end_date))));

		// Get the current number of calls made
		$calls_made = self::getActualCalls($start_date, $end_date, $team_id, $nbm_id);

		// Get the target number of calls that should be made by the end of the month
		$end_of_month_target = self::getTargetCalls($start_date, $month_end_date, $team_id, $nbm_id);

		// Get number of calls that remain to be set
		$remaining_calls = ($end_of_month_target - $calls_made);

		// Get number of working days remaining in month (we minus 1 intentionally)
		$working_days_remaining_in_month = Utils::getWorkingDays($end_date, $month_end_date) - 1;

		// Work out the average number of calls that need to be made for each day remaining
		if ($working_days_remaining_in_month > 0)
		{
			return round($remaining_calls / $working_days_remaining_in_month);
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Get the number of effective calls required to meet the end of month target.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public static function getRequiredEffectives($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		// Work out last day of the month for the period end month
		$month_end_date = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($end_date)) + 1, 0, date('Y', strtotime($end_date))));

		// Get the current number of effectives made
		$effectives_made = self::getActualEffectives($start_date, $end_date, $team_id, $nbm_id);

		// Get the target number of effectives that should be made by the end of the month
		$end_of_month_target = self::getTargetEffectives($start_date, $month_end_date, $team_id, $nbm_id);

		// Get number of effectives that remain to be made
		$remaining_effectives = ($end_of_month_target - $effectives_made);

		// Get number of working days remaining in month (we minus 1 intentionally)
		$working_days_remaining_in_month = Utils::getWorkingDays($end_date, $month_end_date) - 1;

		// Work out the average number of effectives that need to be made for each day remaining
		if ($working_days_remaining_in_month > 0)
		{
			return round($remaining_effectives / $working_days_remaining_in_month);
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Get the number of meetings required to meet the end of month target.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public static function getRequiredMeetingsSet($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		// Work out last day of the month for the period end month
		$month_end_date = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($end_date)) + 1, 0, date('Y', strtotime($end_date))));

		// Get the current number of meeting set
		$meets_set = self::getActualMeetingsSet($start_date, $end_date, $team_id, $nbm_id);

		// Get the target number of meetings that should be set by the end of the month
		$end_of_month_target = self::getTargetMeetingsSet($start_date, $month_end_date, $team_id, $nbm_id);

		// Get number of meets that remain to be set
		$remaining_meets = ($end_of_month_target - $meets_set);

		// Get number of working days remaining in month (we minus 1 intentionally)
		$working_days_remaining_in_month = Utils::getWorkingDays($end_date, $month_end_date) - 1;

		// Work out the average number of calls that need to be made for each day remaining
		if ($working_days_remaining_in_month > 0)
		{
			return round($remaining_meets / $working_days_remaining_in_month);
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Get the number of meetings required to meet the end of month target.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public static function getRequiredMeetingsAttended($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		// Work out last day of the month for the period end month
		$month_end_date = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($end_date)) + 1, 0, date('Y', strtotime($end_date))));

		// Get the current number of meeting set
		$meets_set = self::getActualMeetingsAttended($start_date, $end_date, $team_id, $nbm_id);

		// Get the target number of meetings that should be set by the end of the month
		$end_of_month_target = self::getTargetMeetingsAttended($start_date, $month_end_date, $team_id, $nbm_id);

		// Get number of meets that remain to be set
		$remaining_meets = ($end_of_month_target - $meets_set);

		// Get number of working days remaining in month (we minus 1 intentionally)
		$working_days_remaining_in_month = Utils::getWorkingDays($end_date, $month_end_date) - 1;

		// Work out the average number of calls that need to be made for each day remaining
		if ($working_days_remaining_in_month > 0)
		{
			return ($end_of_month_target - $meets_set) / $working_days_remaining_in_month;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Get the average number of calls made per working day in a given period.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public static function getAverageCallsMade($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		$number = self::getActualCalls($start_date, $end_date, $team_id, $nbm_id, null);
		$working_days = Utils::getWorkingDays($start_date, $end_date);
		return $number / $working_days;
	}

	/**
	 * Get the average number of effective calls made per working day in a given period.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public static function getAverageEffectivesMade($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		$number = self::getActualMeetingsSet($start_date, $end_date, $team_id, $nbm_id, null);
		$working_days = Utils::getWorkingDays($start_date, $end_date);
		return $number / $working_days;
	}

	/**
	 * Get the average number of meetings set per working day in a given period.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public static function getAverageMeetingsSet($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		$number = self::getActualMeetingsSet($start_date, $end_date, $team_id, $nbm_id, null);
		$working_days = Utils::getWorkingDays($start_date, $end_date);
		return $number / $working_days;
	}

	/**
	 * Get the average number of meetings attended per working day in a given period.
	 * @param string $start_date in the format 'YYYY-MM-DD'
	 * @param string $end_date in the format 'YYYY-MM-DD'
	 * @param string $team_id
	 * @param string $nbm_id
	 * @return integer
	 */
	public static function getAverageMeetingsAttended($start_date, $end_date, $team_id = null, $nbm_id = null)
	{
		$number = self::getActualMeetingsAttended($start_date, $end_date, $team_id, $nbm_id, null);
		$working_days = Utils::getWorkingDays($start_date, $end_date);
		return $number / $working_days;
	}

}

?>