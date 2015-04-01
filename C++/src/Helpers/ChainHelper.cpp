#include <Helpers/ChainHelper.hpp>

namespace N2f
{
	ChainHelper::ChainHelper() : ChainHelper(false, false)
	{ }

	ChainHelper::ChainHelper(bool IsEvent, bool DoDebug)
	{
		this->_doDebug = DoDebug;
		this->_isEvent = IsEvent;

		return;
	}

	ChainHelper::~ChainHelper()
	{
		if (this->_nodes.size() > 0)
		{
			this->_nodes.clear();
		}

		return;
	}

	const ChainHelper::NodeList ChainHelper::GetNodes()
	{
		return this->_nodes;
	}

	bool ChainHelper::IsDebug()
	{
		return this->_doDebug;
	}

	bool ChainHelper::IsEvent()
	{
		return this->_isEvent;
	}

	ChainHelper &ChainHelper::LinkNode(std::shared_ptr<NodeBase> Node)
	{
		if (!Node->IsValid())
		{
			return *this;
		}

		if (this->_isEvent && this->_nodes.size() == 1)
		{
			this->_nodes.pop_back();
		}

		this->_nodes.push_back(Node);

		return *this;
	}

	bool ChainHelper::Traverse(void *Sender, std::shared_ptr<DispatchBase> Dispatch)
	{
		if (this->_nodes.size() < 1)
		{
			return false;
		}
		else if (!Dispatch->IsValid())
		{
			return false;
		}
		else if (Dispatch->IsConsumable() && Dispatch->IsConsumed())
		{
			return false;
		}
		else
		{
			bool isConsumable = Dispatch->IsConsumable();

			if (this->_isEvent)
			{
				this->_nodes.back()->Process(Sender, Dispatch);
			}
			else
			{
				for (auto n : this->_nodes)
				{
					n->Process(Sender, Dispatch);

					if (isConsumable && Dispatch->IsConsumed())
					{
						break;
					}
				}
			}
		}

		return true;
	}
}
