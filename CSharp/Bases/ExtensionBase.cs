using System;

namespace N2F
{
	/// <summary>
	/// Abstract base for Extension receivers.
	/// </summary>
	public abstract class ExtensionBase
	{
		/// <summary>
		/// Method to initialize an extension.
		/// </summary>
		/// <param name="N2f">The current executing instance.</param>
		abstract public void Initialize(N2f.N2f N2f);
	}
}
