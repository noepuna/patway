export interface iStaff
{
	auth ?:
	{
		username ?: string
		password ?: string
		rePassword ?: string
	}
	id ?: string
	firstname ?: string
	lastname ?: string
	email ?: string
	location ?: string
	department ?: string
	dateCreated ?: string
	deleted ?: boolean
}