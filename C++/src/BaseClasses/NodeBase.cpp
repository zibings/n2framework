#include <BaseClasses/NodeBase.hpp>
#include <iostream>

namespace N2f
{
	void NodeBase::SetKey(const char *Key)
	{
		if (!Key || strlen(Key) > MAX_NODE_KEY_LENGTH || strlen(Key) < 1)
		{
			return;
		}

		// TODO: Add logging here in the future, for now we're just looking at basic usage
		if (!this->_key || strlen(this->_key) < 1)
		{
#if defined(_MSC_VER)
			strcpy_s(this->_key, sizeof(this->_key), Key);
#else
			strcpy(this->_key, Key);
#endif
		}

		return;
	}

	void NodeBase::SetVersion(const char *Version)
	{
		if (!Version || strlen(Version) > MAX_NODE_VER_LENGTH || strlen(Version) < 1)
		{
			return;
		}

		// TODO: Add logging here in the future, for now we're just looking at basic usage
		if (!this->_version || strlen(this->_version) < 1)
		{
#if defined(_MSC_VER)
			strcpy_s(this->_version, sizeof(this->_version), Version);
#else
			strcpy(this->_version, Version);
#endif
		}

		return;
	}

	NodeBase::NodeBase(const char *Key, const char *Version)
	{
		this->_key[0] = this->_version[0] = 0;

		this->SetKey(Key);
		this->SetVersion(Version);
	}

	const char *NodeBase::GetKey()
	{
		return this->_key;
	}

	const char *NodeBase::GetVersion()
	{
		return this->_version;
	}

	bool NodeBase::IsValid()
	{
		return strlen(this->_key) > 0 && strlen(this->_version) > 0;
	}
}
