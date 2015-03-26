using System;

namespace N2f
{
	/// <summary>
	/// Abstract base for nodes.
	/// </summary>
	public abstract class NodeBase
	{
		protected string _Key;
		protected string _Version;

		/// <summary>
		/// Key value for node.
		/// </summary>
		public string Key { get { return this._Key; } }
		/// <summary>
		/// Version value for node.
		/// </summary>
		public string Version { get { return this._Version; } }

		/// <summary>
		/// Determines whether or not the node is valid.
		/// </summary>
		/// <returns></returns>
		public bool IsValid()
		{
			return !string.IsNullOrEmpty(this._Key) && !string.IsNullOrEmpty(this._Version);
		}

		/// <summary>
		/// Entry point for dispatches being passed along a chain.
		/// </summary>
		/// <param name="Sender">Object which initiated chain traversal.</param>
		/// <param name="Dispatch">The <see cref="DispatchBase"/> to process.</param>
		public abstract void Process(object Sender, DispatchBase Dispatch);

		/// <summary>
		/// Sets the key value for the node.
		/// </summary>
		/// <param name="Key">String value for key.</param>
		/// <returns>The current <see cref="NodeBase"/> instance.</returns>
		protected NodeBase SetKey(string Key)
		{
			this._Key = Key;

			return this;
		}

		/// <summary>
		/// Sets the version value for the node.
		/// </summary>
		/// <param name="Version">String value for version.</param>
		/// <returns>The current <see cref="NodeBase"/> instance.</returns>
		protected NodeBase SetVersion(string Version)
		{
			this._Version = Version;

			return this;
		}
	}
}
