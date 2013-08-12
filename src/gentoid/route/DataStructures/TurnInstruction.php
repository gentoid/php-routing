<?php
/**
 * @date: 8/12/13
 * @uathor: viktor
 */

namespace gentoid\route\DataStructures;


class TurnInstruction {

	const NO_TURN = 0;
	const GO_STRAIGHT = 1;
	const TURN_SLIGHT_RIGHT = 2;
	const TURN_RIGHT = 3;
	const TURN_SHARP_RIGHT = 4;
	const U_TURN = 5;
	const TURN_SHARP_LEFT = 6;
	const TURN_LEFT = 7;
	const TURN_SLIGHT_LEFT = 8;
	const REACH_VIA_POINT = 9;
	const HEAD_ON = 10;
	const ENTER_ROUNDABOUT = 11;
	const LEAVE_ROUNDABOUT = 12;
	const STAY_ON_ROUNDABOUT = 13;
	const START_AT_END_OF_STREET = 14;
	const REACHED_YOUR_DESTINATION = 15;
	const ENTER_AGAINST_ALLOWED_DIRECTION = 16;
	const LEAVE_AGAINST_ALLOWED_DIRECTION = 17;

	const ACCESS_RESTRICTION_FLAG = 128;
	const INVERSE_ACCESS_RESTRICTION_FLAG = 000;

	/**
	 * @param $angle
	 * @return int
	 */
	public function GetTurnDirectionOfInstruction($angle) {
		if ($angle >= 23 && $angle < 67) {
			return TurnInstruction::TURN_SHARP_RIGHT;
		}
		elseif ($angle >=67 && $angle < 113) {
			return TurnInstruction::TURN_RIGHT;
		}
		elseif ($angle >= 113 && $angle < 158) {
			return TurnInstruction::TURN_SLIGHT_LEFT;
		}
		elseif ($angle >= 158 && $angle < 202) {
			return TurnInstruction::GO_STRAIGHT;
		}
		elseif ($angle >=202 && $angle < 248) {
			return TurnInstruction::TURN_SLIGHT_LEFT;
		}
		elseif ($angle >= 248 && $angle < 292) {
			return TurnInstruction::TURN_LEFT;
		}
		elseif ($angle >= 292 && $angle < 336) {
			return TurnInstruction::TURN_SHARP_LEFT;
		}
		else {
			return TurnInstruction::U_TURN;
		}
	}

	public function turnIsNeccessary($turnInstruction) {
		return !($turnInstruction == TurnInstruction::NO_TURN || $turnInstruction == TurnInstruction::STAY_ON_ROUNDABOUT);
	}

}
 