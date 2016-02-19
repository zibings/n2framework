#include <BaseClasses/NodeBase.hpp>

namespace N2f
{
	void NodeBase::SetKey(const char *Key)
	{
		// TODO: Add logging here in the future, for now we're just looking at basic usage
		if ((this->_key == NULL || strlen(this->_key) < 1) && Key != NULL && strlen(Key) > 0)
		{
#if defined(_WIN32)
			strcpy_s(this->_key, sizeof(this->_version), Key);
#else
			strcpy(this->_key, Key);
#endif
		}

		return;
	}

	void NodeBase::SetVersion(const char *Version)
	{
		// TODO: Add logging here in the future, for now we're just looking at basic usage
		if ((this->_version == NULL || strlen(this->_version) < 1) && Version != NULL && strlen(Version) > 0)
		{
#if defined(_WIN32)
			strcpy_s(this->_version, sizeof(this->_version), Version);
#else
			strcpy(this->_version, Version);
#endif
		}

		return;
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
		return this->_key != NULL && strlen(this->_key) > 0 && this->_version != NULL && strlen(this->_version) > 0;
	}
}
