#pragma once

#include <UnitTest++/UnitTest++.h>
#include <N2f.hpp>

#include <iostream>
#include <vector>

class IntegerDispatch : public N2f::DispatchBase
{
public:
	virtual void Initialize() = 0;
	virtual int NumResults() = 0;
	virtual void SetResult(int Result) = 0;
	virtual const std::vector<int> GetResults() = 0;

	void SetResult() { };
};

class NonConsumableStatefulDispatch : public IntegerDispatch
{
public:
	void Initialize() { this->MakeStateful(); this->MakeValid(); }
	int NumResults() { return this->_results.size(); }
	void SetResult(int Result) { this->_results.push_back(Result); }
	const std::vector<int> GetResults() { return this->_results; }

private:
	std::vector<int> _results;
};

class ConsumableStatefulDispatch : public IntegerDispatch
{
public:
	void Initialize() { this->MakeConsumable(); this->MakeStateful(); this->MakeValid(); }
	int NumResults() { return this->_results.size(); }
	void SetResult(int Result) { this->_results.push_back(Result); }
	const std::vector<int> GetResults() { return this->_results; }

private:
	std::vector<int> _results;
};

class NonConsumableNonStatefulDispatch : public IntegerDispatch
{
public:
	NonConsumableNonStatefulDispatch() { this->_result = -1; }
	void Initialize() { this->MakeValid(); }
	int NumResults() { return this->_result == -1 ? 0 : 1; }
	void SetResult(int Result) { this->_result = Result; }
	const std::vector<int> GetResults() { return std::vector<int>(1, this->_result); }

private:
	int _result;
};

class ConsumableNonStatefulDispatch : public IntegerDispatch
{
public:
	ConsumableNonStatefulDispatch() { this->_result = -1; }
	void Initialize() { this->MakeConsumable(); this->MakeValid(); }
	int NumResults() { return this->_result == -1 ? 0 : 1; }
	void SetResult() { }
	void SetResults(int Result) { this->_result = Result; }
	const std::vector<int> GetResults() { return std::vector<int>(1, this->_result); }

private:
	int _result;
};

class IntegerNode : public N2f::NodeBase<IntegerDispatch>
{
public:
	IntegerNode(const char *Key, const char *Version) : N2f::NodeBase<IntegerDispatch>(Key, Version) { }

	virtual void Process(void *Sender, std::shared_ptr<IntegerDispatch> Dispatch) = 0;
};

class NonConsumerNode : public IntegerNode
{
public:
	NonConsumerNode() : IntegerNode("NonConsumerNode", "1") { }

	void Process(void *Sender, std::shared_ptr<IntegerDispatch> Dispatch)
	{
		int newResult = 0;

		for (auto res : Dispatch->GetResults())
		{
			if (res >= newResult)
			{
				newResult = res + 1;
			}
		}

		Dispatch->SetResult(newResult);
		
		return;
	}
};

class ConsumerNode : public IntegerNode
{
public:
	ConsumerNode() : IntegerNode("ConsumerNode", "1") { }

	void Process(void *Sender, std::shared_ptr<IntegerDispatch> Dispatch)
	{
		int newResult = 0;

		for (auto res : Dispatch->GetResults())
		{
			if (res >= newResult)
			{
				newResult = res + 1;
			}
		}

		Dispatch->SetResult(newResult);
		Dispatch->Consume();

		return;
	}
};

SUITE(ChainHelperTest)
{
	TEST(NonConsumableStatefulChainGetsCorrectCount)
	{
		N2f::ChainHelper<IntegerDispatch> chain;

		chain.LinkNode(std::make_shared<NonConsumerNode>());
		chain.LinkNode(std::make_shared<NonConsumerNode>());
		chain.LinkNode(std::make_shared<ConsumerNode>());
		chain.LinkNode(std::make_shared<NonConsumerNode>());

		auto disp = std::make_shared<NonConsumableStatefulDispatch>();
		disp->Initialize();

		chain.Traverse(nullptr, disp);

		CHECK_EQUAL(4, disp->GetResults().size());
	}

	TEST(ConsumableStatefulDispatchGetsCorrectCount)
	{
		N2f::ChainHelper<IntegerDispatch> chain;

		chain.LinkNode(std::make_shared<NonConsumerNode>());
		chain.LinkNode(std::make_shared<NonConsumerNode>());
		chain.LinkNode(std::make_shared<ConsumerNode>());
		chain.LinkNode(std::make_shared<NonConsumerNode>());

		auto disp = std::make_shared<ConsumableStatefulDispatch>();
		disp->Initialize();

		chain.Traverse(nullptr, disp);

		CHECK_EQUAL(3, disp->GetResults().size());
	}
}
