<?php
require_once __DIR__."/db.php";
class ZOHO_MASTER extends DB
{
	private $table="zoho_master";
	// *************** Drop Master table to recreate *************************
	public function dropTable()
	{
		$qrySel="DROP TABLE $this->table";
		$rsltSel=$this->conn->query($qrySel);
		if($rsltSel)
		{
			return  $rsltSel;
		}
		else
		{
			$this->db_error($qrySel);
			return false;
		}
	}
	public function recreateTable()
	{
		$qrySel='CREATE TABLE zoho_master(
			SELECT p.rec_id, p.participant_id, p.booking_id, p.camp_id, p.child_id, p.booking_status, p.is_earlybird, p.status, p.sub_total, p.total, p.discount, p.net_revenue, p.net_revenue_manual, p.camp_sku, p.business_arm, p.dob, p.age, p.gender, p.booking_date, p.booking_date_time, p.participant_type, c.is_partner, c.season, c.year, p.day_of_season, p.calculated_day_of_season, p.week_of_season, p.calculated_week_of_season, b.b_state, b.b_suburb, p.camp_name, c.camp_group, c.camp_suburb, c.camp_state, c.sports, vb.venue_name, vb.rec_id as "venue_booked_unique_id", c.rec_id as "camp_unique_id", b.parent_type, b.family_type
			FROM zoho_participants p
			LEFT JOIN zoho_bookings b
			ON p.booking_id=b.booking_id
			LEFT JOIN zoho_camps c
			ON p.camp_id=c.camp_id
			LEFT JOIN zoho_venues_booked vb
			ON c.venue_booked_id=vb.venues_booked_id
			LEFT JOIN zoho_datewise_events e
			ON p.booking_date=e.event_date
			WHERE c.year>2017
			AND p.booking_status="Booked")';
		$rsltSel=$this->conn->query($qrySel);
		if($rsltSel)
		{
			return  $rsltSel;
		}
		else
		{
			$this->db_error($qrySel);
			return false;
		}
	}
}