using System;
using System.Collections.Generic;

namespace N2f
{
	/// <summary>
	/// Class to enable more stateful return values.
	/// </summary>
	/// <typeparam name="T">Type of result to expect from return.</typeparam>
	public class ReturnHelper<T>
	{
		private List<string> _Messages;
		private List<T> _Results;
		private ReturnStatuses _Status;

		/// <summary>
		/// Collection of execution messages.
		/// </summary>
		public List<string> Messages { get { return this._Messages; } }
		/// <summary>
		/// Collection of execution results of type {T}.
		/// </summary>
		public List<T> Results { get { return this._Results; } }
		/// <summary>
		/// Status of execution.
		/// </summary>
		public ReturnStatuses Status { get { return this._Status; } }
	}

	public enum ReturnStatuses
	{
		Bad = 0,
		Gud
	}
}
