using System;
using System.Collections.Generic;

namespace N2F
{
	public abstract class DispatchBase
	{
		protected List<object> _Results;
		protected bool _IsConsumable;
		protected bool _IsStateful;
		protected bool _IsConsumed;
		protected bool _IsValid;

		public List<object> Results { get { return this._Results; } }
		public bool IsConsumable { get { return this._IsConsumable; } }
		public bool IsStateful { get { return this._IsStateful; } }
		public bool IsConsumed { get { return this._IsConsumed; } }
		public bool IsValid { get { return this._IsValid; } }

		public bool Consume()
		{
			if (this._IsConsumable && !this._IsConsumed)
			{
				this._IsConsumed = true;

				return true;
			}

			return false;
		}

		public virtual List<object> GetResults()
		{
			return new List<object>(this._Results);
		}

		public abstract void Initialize(object Input);

		protected void MakeConsumable()
		{
			this._IsConsumable = true;

			return;
		}

		protected void MakeStateful()
		{
			this._IsStateful = true;

			return;
		}

		protected void MakeValid()
		{
			this._IsValid = true;

			return;
		}

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
