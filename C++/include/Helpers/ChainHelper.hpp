#pragma once

#include <BaseClasses/NodeBase.hpp>
#include <memory>
#include <vector>

namespace N2f
{
	class ChainHelper
	{
	public:
		typedef std::vector<std::shared_ptr<NodeBase>> NodeList;
		typedef std::vector<std::shared_ptr<NodeBase>>::iterator NodeListIter;

	protected:
		bool _doDebug = false, _isEvent = false;
		NodeList _nodes;

	public:

		////////////////////////////////////////////////////////////////////////////////////////////////////
		/// <summary>	Default constructor, chain will not be an event and will not debug. </summary>
		///
		/// <remarks>	Andrew, 4/1/2015. </remarks>
		////////////////////////////////////////////////////////////////////////////////////////////////////
		ChainHelper();

		////////////////////////////////////////////////////////////////////////////////////////////////////
		/// <summary>	Main constructor, allows specification of event and debug statuses. </summary>
		///
		/// <remarks>	Andrew, 4/1/2015. </remarks>
		///
		/// <param name="IsEvent">	true if ChainHelper instance is an event. </param>
		/// <param name="DoDebug">	true if ChainHelper instance should show debug information. </param>
		////////////////////////////////////////////////////////////////////////////////////////////////////
		ChainHelper(bool IsEvent, bool DoDebug);

		////////////////////////////////////////////////////////////////////////////////////////////////////
		/// <summary>	Virtual destructor for cleanup. </summary>
		///
		/// <remarks>	Andrew, 4/1/2015. </remarks>
		////////////////////////////////////////////////////////////////////////////////////////////////////
		virtual ~ChainHelper();

		////////////////////////////////////////////////////////////////////////////////////////////////////
		/// <summary>	Returns the current list of nodes. </summary>
		///
		/// <remarks>	Andrew, 4/1/2015. </remarks>
		///
		/// <returns>	Vector of NodeBase nodes linked into chain. </returns>
		////////////////////////////////////////////////////////////////////////////////////////////////////
		const NodeList GetNodes();

		////////////////////////////////////////////////////////////////////////////////////////////////////
		/// <summary>	Whether or not this ChainHelper instance is producing debug information. </summary>
		///
		/// <remarks>	Andrew, 4/1/2015. </remarks>
		///
		/// <returns>	true if producing debug information, false if not. </returns>
		////////////////////////////////////////////////////////////////////////////////////////////////////
		bool IsDebug();

		////////////////////////////////////////////////////////////////////////////////////////////////////
		/// <summary>
		///		Whether or not this ChainHelper instance is an event, meaning it will only allow one NodeBase
		///		to be linked at any time.
		///	</summary>
		///
		/// <remarks>	Andrew, 4/1/2015. </remarks>
		///
		/// <returns>	true if event, false if not. </returns>
		////////////////////////////////////////////////////////////////////////////////////////////////////
		bool IsEvent();

		////////////////////////////////////////////////////////////////////////////////////////////////////
		/// <summary>	Links a node into the ChainHelper instance. </summary>
		///
		/// <remarks>	Andrew, 4/1/2015. </remarks>
		///
		/// <param name="Node">	The NodeBase node to link. </param>
		///
		/// <returns>	A reference to the ChainHelper instance. </returns>
		////////////////////////////////////////////////////////////////////////////////////////////////////
		ChainHelper &LinkNode(std::shared_ptr<NodeBase> Node);

		////////////////////////////////////////////////////////////////////////////////////////////////////
		/// <summary>	Triggers the traversal of the chain. </summary>
		///
		/// <remarks>	Andrew, 4/1/2015. </remarks>
		///
		/// <param name="Sender">	[in,out] If non-null, the sender. </param>
		/// <param name="Dispatch">	The dispatch to send along the chain. </param>
		///
		/// <returns>	true if traversal happens, false if it fails. </returns>
		////////////////////////////////////////////////////////////////////////////////////////////////////
		bool Traverse(void *Sender, std::shared_ptr<DispatchBase> Dispatch);
	};
}
