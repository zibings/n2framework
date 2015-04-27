using System;

namespace N2f
{
	/// <summary>
	/// A command line argument that has been parsed.
	/// </summary>
	public struct ParsedArgument
	{
		/// <summary>
		/// The key of the argument.
		/// </summary>
		public string Key { get; set; }
		/// <summary>
		/// The lowercase version of the key.
		/// </summary>
		public string LoweredKey { get; set; }
		/// <summary>
		/// The original string that was parsed.
		/// </summary>
		public string Original { get; set; }
		/// <summary>
		/// The value of the argument.
		/// </summary>
		public string Value { get; set; }
	}
}
