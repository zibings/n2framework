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
		/// <summary>
		/// Whether or not the instance is BAD.
		/// </summary>
		public bool IsBad { get { return this._Status == ReturnStatuses.BAD; } }
		/// <summary>
		/// Alias of IsGud for those with stricter english requirements.
		/// </summary>
		public bool IsGood { get { return this.IsGud; } }
		/// <summary>
		/// Whether or not the instance is GUD.
		/// </summary>
		public bool IsGud { get { return this._Status == ReturnStatuses.GUD; } }

		/// <summary>
		/// Creates a new ReturnHelper instance, defaults to BAD status.
		/// </summary>
		public ReturnHelper()
		{
			this._Messages = new List<string>();
			this._Results = new List<T>();
			this._Status = ReturnStatuses.BAD;

			return;
		}

		/// <summary>
		/// Sets the instance status to BAD.
		/// </summary>
		public void SetBad()
		{
			this._Status = ReturnStatuses.BAD;

			return;
		}

		/// <summary>
		/// Alias of SetGud() for those with stricter english requirements.
		/// </summary>
		public void SetGood()
		{
			this.SetGud();

			return;
		}

		/// <summary>
		/// Sets the instance status to GUD.
		/// </summary>
		public void SetGud()
		{
			this._Status = ReturnStatuses.GUD;

			return;
		}

		/// <summary>
		/// Sets the instance status.
		/// </summary>
		/// <param name="Status">New status for instance.</param>
		public void SetStatus(ReturnStatuses Status)
		{
			this._Status = Status;

			return;
		}
	}

	/// <summary>
	/// ReturnHelper statuses.
	/// </summary>
	public enum ReturnStatuses
	{
		BAD = 0,
		GUD
	}
}
