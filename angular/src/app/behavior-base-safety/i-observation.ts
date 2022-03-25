export interface iObservation
{
	id ?: string
	types ?: string[]
	properties  ?: { [key: string] : string | number }
	observer ?: string
	supervisor ?: string
	notes ?: string
	recommendation ?: string
	actionRequired ?: string 
	actionTaken ?: string
	feedbackToCoworkers ?: string
	createdBy ?: string
	dateCreated ?: number
	deleted ?: boolean
}
