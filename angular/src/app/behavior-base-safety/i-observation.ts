export interface iObservation
{
	id ?: string
	types ?: string[]
	properties  ?: { [key: string] : string | number }
	observer ?: string
	supervisor ?: string
	notes ?: string
	recommendation ?: string
<<<<<<< HEAD
	actionRequired ?: string 
=======
>>>>>>> 50cddb0018e73587d801050aa03ad33cec65b210
	actionTaken ?: string
	feedbackToCoworkers ?: string
	createdBy ?: string
	dateCreated ?: number
	deleted ?: boolean
}
