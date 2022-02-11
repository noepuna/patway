export interface iAccount
{
	id ?: string,
	firstname ?: string,
	middlename ?: string,
	lastname ?: string,
	fullname ?: string,
	profilePhoto ?:
	{
		id : string,
		url : string
	},
	isStaff ?: boolean
	isAdmin ?: boolean
	isSuper ?: boolean
}
