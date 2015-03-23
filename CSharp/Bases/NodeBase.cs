using System;

namespace N2F
{
	public abstract class NodeBase
	{
		protected string _Key;
		protected string _Version;

		public string Key { get { return this._Key; } }
		public string Version { get { return this._Version; } }

		public bool IsValid()
		{
			return !string.IsNullOrEmpty(this._Key) && !string.IsNullOrEmpty(this._Version);
		}

		public abstract void Process(object Sender, DispatchBase Dispatch);

		protected NodeBase SetKey(string Key)
		{
			this._Key = Key;

			return this;
		}

		protected NodeBase SetVersion(string Version)
		{
			this._Version = Version;

			return this;
		}
	}
}
