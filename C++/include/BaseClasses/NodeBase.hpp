#pragma once

#include <BaseClasses/DispatchBase.hpp>
#include <memory>

namespace N2f
{
	////////////////////////////////////////////////////////////////////////////////////////////////////
	/// <summary>
	///		Abstract base class for nodes used to process information
	///		sent along chains.
	/// </summary>
	///
	/// <remarks>	Andrew, 4/1/2015. </remarks>
	////////////////////////////////////////////////////////////////////////////////////////////////////
	class NodeBase
	{
	protected:
		char *_key, *_version;

		void SetKey();
		void SetVersion();

	public:
		virtual ~NodeBase() { }
		virtual void Process(void *Sender, std::shared_ptr<DispatchBase> Dispatch);

		char *GetKey();
		char *GetVersion();
		bool IsValid();
	};
}
