using System;
using System.Collections.Generic;
using System.Text;

namespace N2f
{
	public class CliDispatch : DispatchBase
	{
		private bool _IsWindows;

		public bool IsWindows { get { return this._IsWindows; } }

		public CliDispatch()
		{
			switch (Environment.OSVersion.Platform)
			{
				case PlatformID.MacOSX:
				case PlatformID.Unix:
					this._IsWindows = false;

					break;
				default:
					this._IsWindows = true;

					break;
			}

			return;
		}

		public override void Initialize(object Input)
		{
			throw new NotImplementedException();
		}
	}
}
