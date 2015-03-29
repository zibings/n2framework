using System;
using System.Collections.Generic;
using System.Text;

namespace N2f
{
	public class CliDispatch : DispatchBase
	{
		private ConsoleHelper _ConsoleHelper;

		public bool IsWindows { get { return this._ConsoleHelper.IsWindows; } }

		public CliDispatch()
		{
			return;
		}

		public override void Initialize(object Input)
		{
			throw new NotImplementedException();
		}
	}
}
