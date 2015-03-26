using System;
using System.Collections.Generic;

namespace N2f
{
	/// <summary>
	/// Abstract base for dispatches.
	/// </summary>
	public abstract class DispatchBase
	{
		protected DateTime _CalledDateTime;
		protected List<object> _Results;
		protected bool _IsConsumable;
		protected bool _IsStateful;
		protected bool _IsConsumed;
		protected bool _IsValid;

		/// <summary>
		/// DateTime when dispatch was marked as valid.
		/// </summary>
		public DateTime CalledDateTime { get { return this._CalledDateTime; } }
		/// <summary>
		/// Collection of results for the dispatch.
		/// </summary>
		public List<object> Results { get { return this._Results; } }
		/// <summary>
		/// Whether or not the dispatch is consumable.
		/// </summary>
		public bool IsConsumable { get { return this._IsConsumable; } }
		/// <summary>
		/// Whether or not the dispatch is stateful (can have multiple results).
		/// </summary>
		public bool IsStateful { get { return this._IsStateful; } }
		/// <summary>
		/// Whether or not the dispatch is consumed.
		/// </summary>
		public bool IsConsumed { get { return this._IsConsumed; } }
		/// <summary>
		/// Whether or not the dispatch is valid.
		/// </summary>
		public bool IsValid { get { return this._IsValid; } }

		/// <summary>
		/// Marks the dispatch as consumed if it is consumable (and not consumed).
		/// </summary>
		/// <returns>Whether or not the consume action was successful.</returns>
		public bool Consume()
		{
			if (this._IsConsumable && !this._IsConsumed)
			{
				this._IsConsumed = true;

				return true;
			}

			return false;
		}

		/// <summary>
		/// Returns the DateTime the dispatch was marked valid.
		/// </summary>
		/// <returns></returns>
		public DateTime GetCalledDateTime()
		{
			return this._CalledDateTime;
		}

		/// <summary>
		/// Returns the collection of results (if any) from the dispatch.
		/// </summary>
		/// <returns>Any results assigned to the dispatch.</returns>
		public virtual List<object> GetResults()
		{
			return new List<object>(this._Results);
		}

		/// <summary>
		/// Method to initialize the dispatch for processing.
		/// </summary>
		/// <param name="Input">Input information to initialize the dispatch.</param>
		public abstract void Initialize(object Input);

		/// <summary>
		/// Marks the dispatch as consumable.
		/// </summary>
		protected void MakeConsumable()
		{
			this._IsConsumable = true;

			return;
		}

		/// <summary>
		/// Marks the dispatch as stateful (able to have multiple results).
		/// </summary>
		protected void MakeStateful()
		{
			this._IsStateful = true;

			return;
		}

		/// <summary>
		/// Marks the dispatch as valid (ready for processing).
		/// </summary>
		protected void MakeValid()
		{
			this._IsValid = true;

			return;
		}

		/// <summary>
		/// Sets a result for the dispatch, either adding it to the collection if the dispatch is stateful, or adding it as the only result if not stateful.
		/// </summary>
		/// <param name="Result">Object to set as result.</param>
		/// <returns>The current <see cref="DispatchBase"/> instance.</returns>
		public DispatchBase SetResult(object Result)
		{
			if (!this._IsStateful)
			{
				this._Results = new List<object>(new object[] { Result });
			}
			else
			{
				this._Results.Add(Result);
			}

			return this;
		}
	}
}
