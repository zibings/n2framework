using System;
using System.Collections.Generic;

namespace N2f
{
	/// <summary>
	/// Class to create a chain full of nodes.
	/// </summary>
	public class ChainHelper : IDisposable
	{
		protected List<NodeBase> _Nodes;
		protected Logger _Logger;
		protected bool _Debug;
		protected bool _Event;

		/// <summary>
		/// Collection of <see cref="NodeBase"/> nodes linked to chain.
		/// </summary>
		public List<NodeBase> Nodes { get { return new List<NodeBase>(this._Nodes); } }
		/// <summary>
		/// Whether or not debugging information is being produced.
		/// </summary>
		public bool IsDebug { get { return this._Debug == true; } }
		/// <summary>
		/// Whether or not this is an event chain (only one node can subscribe at a time).
		/// </summary>
		public bool IsEvent { get { return this._Event == true; } }
		/// <summary>
		/// Local Logger instance for debug information, if enabled.
		/// </summary>
		public Logger Logger { get { return this._Logger; } }

		/// <summary>
		/// Creates a new ChainHelper instance.
		/// </summary>
		/// <param name="IsEvent">True if chain is an event, default is false.</param>
		/// <param name="IsDebug">True if chain should produce debugging information, default is false.</param>
		/// <param name="Logger">Optional Logger instance to send debug information, if enabled.</param>
		public ChainHelper(bool IsEvent = false, bool IsDebug = false, Logger Logger = null)
		{
			this._Logger = (Logger != null) ? Logger : new Logger();
			this._Nodes = new List<NodeBase>();
			this._Debug = IsDebug;
			this._Event = IsEvent;

			return;
		}

		/// <summary>
		/// Links a NodeBase instance into the chain, if valid.
		/// </summary>
		/// <param name="Node">NodeBase instance to link to chain.</param>
		/// <returns>The current <see cref="ChainHelper{T}"/> instance.</returns>
		public ChainHelper LinkNode(NodeBase Node)
		{
			if (!Node.IsValid())
			{
				if (this._Debug)
				{
					// TODO: Logger gets called here; Invalid node, could not link into chain.
				}

				return this;
			}

			if (this._Event)
			{
				this._Nodes = new List<NodeBase>() { Node };

				if (this._Debug)
				{
					// TODO: Logger gets called here; Set {$Node->GetKey()} (v{$Node->GetVersion()}) node as chain handler.
				}
			}
			else
			{
				this._Nodes.Add(Node);

				if (this._Debug)
				{
					// TODO: Logger gets called here; Linked {$Node->GetKey()} (v{$Node->GetVersion()}) node to chain.
				}
			}

			return this;
		}

		/// <summary>
		/// Starts traversal of the chain provided the chain has linked nodes, the dispatch is valid,
		/// and the dispatch hasn't already been consumed.
		/// </summary>
		/// <param name="Dispatch"><typeparamref name="T"/> dispatch to send along chain.</param>
		/// <param name="Sender">Optional reference to entity that started traversal, default is assigned to this <see cref="ChainHelper{T}"/> instance.</param>
		/// <returns>A <see cref="ReturnHelper{T}"/> instance with extra state information.</returns>
		public ReturnHelper<DispatchBase> Traverse(DispatchBase Dispatch, object Sender = null)
		{
			ReturnHelper<DispatchBase> Ret = new ReturnHelper<DispatchBase>();

			if (this._Nodes.Count < 1)
			{
				Ret.SetMessage("No nodes linked to chain.");
			}
			else if (!Dispatch.IsValid)
			{
				Ret.SetMessage("Invalid dispatch.");
			}
			else if (Dispatch.IsConsumed)
			{
				Ret.SetMessage("Process attempt on dispatch that is already consumed.");
			}
			else
			{
				if (Sender == null)
				{
					Sender = this;
				}

				bool IsConsumable = Dispatch.IsConsumable;

				if (this._Event)
				{
					this._Nodes[0].Process(Sender, Dispatch);

					if (this._Debug)
					{
						// TODO: Logger gets called here; Sending dispatch to {$this->_Nodes[$i]->GetKey()} (v{$this->_Nodes[$i]->GetVersion()}) node in chain.
					}
				}
				else
				{
					foreach (var N in this._Nodes)
					{
						if (this._Debug)
						{
							// TODO: Logger gets called here; Sending dispatch to {$this->_Nodes[$i]->GetKey()} (v{$this->_Nodes[$i]->GetVersion()}) node in chain.
						}

						N.Process(Sender, Dispatch);

						if (IsConsumable && Dispatch.IsConsumed)
						{
							if (this._Debug)
							{
								// TODO: Logger gets called here; Chain traversal stopped by {$this->_Nodes[$i]->GetKey()} (v{$this->_Nodes[$i]->GetVersion()}) node.
							}

							break;
						}
					}
				}
			}

			Ret.SetGud();

			return Ret;
		}

		/// <summary>
		/// Dispose method to make sure we clean things up.
		/// </summary>
		public void Dispose()
		{
			if (this._Nodes != null)
			{
				this._Nodes.Clear();
				this._Nodes = null;
			}

			return;
		}
	}
}
