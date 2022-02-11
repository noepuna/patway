export interface iFilter
{
	name : string
	value ?: string | number | Array<string|number>
	arithmetic_operator ?: "<>" | "=" | ">=" | "<="
	logic_operator ?: "AND" | "OR"
}