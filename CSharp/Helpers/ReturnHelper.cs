using System;
using System.Collections.Generic;

namespace N2f
{
	/// <summary>
	/// Class to enable more stateful return values.
	/// </summary>
	/// <typeparam name="T">Type of result to expect from return.</typeparam>
	public class ReturnHelper<T> : IDisposable
	{
		protected List<string> _Messages;
		protected List<T> _Results;
		protected ReturnStatuses _Status;

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
		/// Whether or not execution provided messages.
		/// </summary>
		public bool HasMessages { get { return this._Messages.Count > 0; } }
		/// <summary>
		/// Whether or not execution yielded results.
		/// </summary>
		public bool HasResults { get { return this._Results.Count > 0; } }
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
		/// Adds a message to the instance.
		/// </summary>
		/// <param name="Message">String value of message to add.</param>
		/// <returns>The current <see cref="ReturnHelper{T}"/> instance.</returns>
		public ReturnHelper<T> SetMessage(string Message)
		{
			if (string.IsNullOrWhiteSpace(Message))
			{
				return this;
			}

			this._Messages.Add(Message);

			return this;
		}

		/// <summary>
		/// Adds an array of messages to the instance.
		/// </summary>
		/// <param name="Messages">Array of messages to add.</param>
		/// <returns>The current <see cref="ReturnHelper{T}"/> instance.</returns>
		public ReturnHelper<T> SetMessages(string[] Messages)
		{
			if (Messages != null && Messages.Length > 0)
			{
				foreach (var m in Messages)
				{
					this.SetMessage(m);
				}
			}

			return this;
		}

		/// <summary>
		/// Adds a collection of messages to the instance.
		/// </summary>
		/// <param name="Messages">Collection of messages to add.</param>
		/// <returns>The current <see cref="ReturnHelper{T}"/> instance.</returns>
		public ReturnHelper<T> SetMessages(IEnumerable<string> Messages)
		{
			if (Messages != null)
			{
				foreach (var m in Messages)
				{
					this.SetMessage(m);
				}
			}

			return this;
		}

		/// <summary>
		/// Adds a result to the instance.
		/// </summary>
		/// <param name="Result"><typeparamref name="T"/> value of result to add.</param>
		/// <returns>The current <see cref="ReturnHelper{T}"/> instance.</returns>
		public ReturnHelper<T> SetResult(T Result)
		{
			if (Result != null)
			{
				this._Results.Add(Result);
			}

			return this;
		}

		/// <summary>
		/// Adds an array of results to the instance.
		/// </summary>
		/// <param name="Results">Array of <typeparamref name="T"/> results to add.</param>
		/// <returns>The current <see cref="ReturnHelper{T}"/> isntance.</returns>
		public ReturnHelper<T> SetResults(T[] Results)
		{
			if (Results != null && Results.Length > 0)
			{
				foreach (var r in Results)
				{
					this.SetResult(r);
				}
			}

			return this;
		}

		/// <summary>
		/// Adds a collection of results to the instance.
		/// </summary>
		/// <param name="Results">Collection of <typeparamref name="T"/> results to add.</param>
		/// <returns>The current <see cref="ReturnHelper{T}"/> instance.</returns>
		public ReturnHelper<T> SetResults(IEnumerable<T> Results)
		{
			if (Results != null)
			{
				foreach (var r in Results)
				{
					this.SetResult(r);
				}
			}

			return this;
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

		/// <summary>
		/// Dispose method to make sure we clean things up.
		/// </summary>
		public void Dispose()
		{
			if (this._Messages != null)
			{
				this._Messages.Clear();
				this._Messages = null;
			}

			if (this._Results != null)
			{
				this._Results.Clear();
				this._Results = null;
			}

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
