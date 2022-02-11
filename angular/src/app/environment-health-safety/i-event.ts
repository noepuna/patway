import { iStaff } from '../backoffice/staff/i-staff';
import { iHashTag } from '../messaging/i-hash-tag';

export interface iEvent
{
	id ?: string
	requirementId ?: string
	riskLevel ?: string
	message ?: string
	title ?: string
	location ?: string
	description ?: string
	dateStart ?: number
	dateEnd ?: number | null
	recipients ?: iStaff[]
	status ?: string,
	hashtagEntries ?: iHashTag[]
}